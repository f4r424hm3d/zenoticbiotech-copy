<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DefaultOgImage;
use App\Models\DynamicPageSeo;
use App\Models\Lead;
use App\Models\Product;
use App\Models\ProductContent;
use App\Models\ProductFaq;
use App\Models\Service;
use App\Models\ServiceContent;
use App\Models\ServiceFaq;
use App\Models\StaticPageSeo;
use App\Models\SystemSetting;
use App\Models\UploadFile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminPageController extends Controller
{
    public function login(): View|RedirectResponse
    {
        return Auth::check() ? redirect()->route('admin.dashboard') : view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate(['email' => ['required', 'email'], 'password' => ['required', 'string']]);
        $credentials['email'] = strtolower(trim($credentials['email']));

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }

        if ($request->user()?->role !== 'admin') {
            Auth::logout();
            return back()->withErrors(['email' => 'Admin access required.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'productCount' => Product::count(),
            'publishedCount' => Product::where('status', 'published')->count(),
            'draftCount' => Product::where('status', 'draft')->count(),
            'categoryCount' => Category::count(),
            'serviceCount' => Service::count(),
            'leadCount' => Lead::count(),
            'latestProducts' => Product::with('category')->latest()->take(5)->get(),
            'latestLeads' => Lead::latest()->take(5)->get(),
        ]);
    }

    public function profile(): View { return view('admin.profile'); }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate(['current_password' => ['required', 'string'], 'password' => ['required', 'confirmed', PasswordRule::min(8)]]);

        if (! Hash::check($data['current_password'], $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $request->user()->forceFill(['password' => Hash::make($data['password']), 'remember_token' => Str::random(60)])->save();

        return back()->with('status', 'Password changed successfully.');
    }

    public function products(Request $request): View
    {
        $query = Product::query()->with('category');

        if ($request->filled('search')) {
            $search = (string) $request->query('search');
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%"));
        }

        if ($request->filled('segment') && in_array($request->query('segment'), ['human', 'animal'], true)) {
            $query->where('segment', $request->query('segment'));
        }

        if ($request->filled('status') && in_array($request->query('status'), ['published', 'draft'], true)) {
            $query->where('status', $request->query('status'));
        }

        return view('admin.products.index', [
            'products' => $query->withCount(['contents', 'faqs'])->orderBy('order_index')->orderByDesc('created_at')->paginate(15)->withQueryString(),
        ]);
    }

    public function createProduct(): View
    {
        return view('admin.products.form', ['product' => new Product(['segment' => 'human', 'status' => 'draft', 'order_index' => 0]), 'categories' => Category::orderBy('order_index')->orderBy('name')->get()]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $product = Product::create($this->validatedProductData($request));

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product created.');
    }

    public function editProduct(Product $product): View
    {
        return view('admin.products.form', ['product' => $product, 'categories' => Category::orderBy('order_index')->orderBy('name')->get()]);
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedProductData($request, $product));

        return back()->with('status', 'Product updated.');
    }

    public function toggleProduct(Product $product): RedirectResponse
    {
        $product->update(['status' => $product->status === 'published' ? 'draft' : 'published']);

        return back()->with('status', 'Product status updated.');
    }

    public function deleteProduct(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product deleted.');
    }

    public function productContents(Product $product): View
    {
        return view('admin.products.contents', [
            'product' => $product,
            'contents' => $product->contents()->with('parent')->get(),
            'parentContents' => $product->parentContents()->get(),
            'content' => new ProductContent(['position' => $product->contents()->count() + 1]),
        ]);
    }

    public function storeProductContent(Request $request, Product $product): RedirectResponse
    {
        $product->contents()->create($this->validatedProductContentData($request, $product));

        return back()->with('status', 'Product content created.');
    }

    public function editProductContent(Product $product, ProductContent $content): View
    {
        abort_unless($content->product_id === $product->id, 404);

        return view('admin.products.contents', [
            'product' => $product,
            'contents' => $product->contents()->with('parent')->get(),
            'parentContents' => $product->parentContents()->whereKeyNot($content->id)->get(),
            'content' => $content,
        ]);
    }

    public function updateProductContent(Request $request, Product $product, ProductContent $content): RedirectResponse
    {
        abort_unless($content->product_id === $product->id, 404);
        $content->update($this->validatedProductContentData($request, $product, $content));

        return redirect()->route('admin.products.contents', $product)->with('status', 'Product content updated.');
    }

    public function deleteProductContent(Product $product, ProductContent $content): RedirectResponse
    {
        abort_unless($content->product_id === $product->id, 404);
        $content->delete();

        return back()->with('status', 'Product content deleted.');
    }

    public function productFaqs(Product $product): View
    {
        return view('admin.products.faqs', [
            'product' => $product,
            'faqs' => $product->faqs()->get(),
            'faq' => new ProductFaq(),
        ]);
    }

    public function storeProductFaq(Request $request, Product $product): RedirectResponse
    {
        $product->faqs()->create($request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]));

        return back()->with('status', 'Product FAQ created.');
    }

    public function editProductFaq(Product $product, ProductFaq $faq): View
    {
        abort_unless($faq->product_id === $product->id, 404);

        return view('admin.products.faqs', [
            'product' => $product,
            'faqs' => $product->faqs()->get(),
            'faq' => $faq,
        ]);
    }

    public function updateProductFaq(Request $request, Product $product, ProductFaq $faq): RedirectResponse
    {
        abort_unless($faq->product_id === $product->id, 404);
        $faq->update($request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]));

        return redirect()->route('admin.products.faqs', $product)->with('status', 'Product FAQ updated.');
    }

    public function deleteProductFaq(Product $product, ProductFaq $faq): RedirectResponse
    {
        abort_unless($faq->product_id === $product->id, 404);
        $faq->delete();

        return back()->with('status', 'Product FAQ deleted.');
    }

    public function categories(): View
    {
        return view('admin.categories.index', ['categories' => Category::orderBy('order_index')->orderBy('name')->get()]);
    }

    public function storeCategory(Request $request): RedirectResponse
    {
        Category::create($request->validate(['name' => ['required', 'string', 'max:255', 'unique:categories,name'], 'description' => ['nullable', 'string'], 'order_index' => ['nullable', 'integer']]));

        return back()->with('status', 'Category created.');
    }

    public function updateCategory(Request $request, Category $category): RedirectResponse
    {
        $category->update($request->validate(['name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id], 'description' => ['nullable', 'string'], 'order_index' => ['nullable', 'integer']]));

        return back()->with('status', 'Category updated.');
    }

    public function deleteCategory(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['category' => 'Cannot delete a category that still has products.']);
        }

        $category->delete();

        return back()->with('status', 'Category deleted.');
    }

    public function services(): View
    {
        return view('admin.services.index', [
            'services' => Service::withCount(['contents', 'faqs'])->orderBy('order_index')->orderBy('name')->get(),
        ]);
    }

    public function createService(): View
    {
        return view('admin.services.form', [
            'service' => new Service(['status' => 'published', 'icon' => 'circle', 'emoji' => '&#9679;', 'order_index' => 0]),
        ]);
    }

    public function storeService(Request $request): RedirectResponse
    {
        $service = Service::create($this->validatedServiceData($request));

        return redirect()->route('admin.services.edit', $service)->with('status', 'Service created.');
    }

    public function editService(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function updateService(Request $request, Service $service): RedirectResponse
    {
        $service->update($this->validatedServiceData($request, $service));

        return back()->with('status', 'Service updated.');
    }

    public function toggleService(Service $service): RedirectResponse
    {
        $service->update(['status' => $service->status === 'published' ? 'draft' : 'published']);

        return back()->with('status', 'Service status updated.');
    }

    public function deleteService(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.services')->with('status', 'Service deleted.');
    }

    public function serviceContents(Service $service): View
    {
        return view('admin.services.contents', [
            'service' => $service,
            'contents' => $service->contents()->with('parent')->get(),
            'parentContents' => $service->parentContents()->get(),
            'content' => new ServiceContent(['position' => $service->contents()->count() + 1]),
        ]);
    }

    public function storeServiceContent(Request $request, Service $service): RedirectResponse
    {
        $service->contents()->create($this->validatedServiceContentData($request, $service));

        return back()->with('status', 'Service content created.');
    }

    public function editServiceContent(Service $service, ServiceContent $content): View
    {
        abort_unless($content->service_id === $service->id, 404);

        return view('admin.services.contents', [
            'service' => $service,
            'contents' => $service->contents()->with('parent')->get(),
            'parentContents' => $service->parentContents()->whereKeyNot($content->id)->get(),
            'content' => $content,
        ]);
    }

    public function updateServiceContent(Request $request, Service $service, ServiceContent $content): RedirectResponse
    {
        abort_unless($content->service_id === $service->id, 404);
        $content->update($this->validatedServiceContentData($request, $service, $content));

        return redirect()->route('admin.services.contents', $service)->with('status', 'Service content updated.');
    }

    public function deleteServiceContent(Service $service, ServiceContent $content): RedirectResponse
    {
        abort_unless($content->service_id === $service->id, 404);
        $content->delete();

        return back()->with('status', 'Service content deleted.');
    }

    public function serviceFaqs(Service $service): View
    {
        return view('admin.services.faqs', [
            'service' => $service,
            'faqs' => $service->faqs()->get(),
            'faq' => new ServiceFaq(),
        ]);
    }

    public function storeServiceFaq(Request $request, Service $service): RedirectResponse
    {
        $service->faqs()->create($request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]));

        return back()->with('status', 'Service FAQ created.');
    }

    public function editServiceFaq(Service $service, ServiceFaq $faq): View
    {
        abort_unless($faq->service_id === $service->id, 404);

        return view('admin.services.faqs', [
            'service' => $service,
            'faqs' => $service->faqs()->get(),
            'faq' => $faq,
        ]);
    }

    public function updateServiceFaq(Request $request, Service $service, ServiceFaq $faq): RedirectResponse
    {
        abort_unless($faq->service_id === $service->id, 404);
        $faq->update($request->validate([
            'question' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]));

        return redirect()->route('admin.services.faqs', $service)->with('status', 'Service FAQ updated.');
    }

    public function deleteServiceFaq(Service $service, ServiceFaq $faq): RedirectResponse
    {
        abort_unless($faq->service_id === $service->id, 404);
        $faq->delete();

        return back()->with('status', 'Service FAQ deleted.');
    }

    public function staticPageSeos(): View
    {
        return view('admin.seo.page-seos', [
            'title' => 'Static Page SEO',
            'description' => 'Manage SEO metadata for fixed website pages.',
            'records' => StaticPageSeo::latest()->get(),
            'record' => new StaticPageSeo(),
            'type' => 'static',
            'routePrefix' => 'admin.static-page-seos',
        ]);
    }

    public function storeStaticPageSeo(Request $request): RedirectResponse
    {
        StaticPageSeo::create($this->validatedSeoData($request, 'static_page_seos'));

        return back()->with('status', 'Static page SEO created.');
    }

    public function editStaticPageSeo(StaticPageSeo $staticPageSeo): View
    {
        return view('admin.seo.page-seos', [
            'title' => 'Static Page SEO',
            'description' => 'Manage SEO metadata for fixed website pages.',
            'records' => StaticPageSeo::latest()->get(),
            'record' => $staticPageSeo,
            'type' => 'static',
            'routePrefix' => 'admin.static-page-seos',
        ]);
    }

    public function updateStaticPageSeo(Request $request, StaticPageSeo $staticPageSeo): RedirectResponse
    {
        $staticPageSeo->update($this->validatedSeoData($request, 'static_page_seos', $staticPageSeo->id, $staticPageSeo->og_image_path));

        return redirect()->route('admin.static-page-seos')->with('status', 'Static page SEO updated.');
    }

    public function deleteStaticPageSeo(StaticPageSeo $staticPageSeo): RedirectResponse
    {
        $this->deletePublicFile($staticPageSeo->og_image_path);
        $staticPageSeo->delete();

        return back()->with('status', 'Static page SEO deleted.');
    }

    public function dynamicPageSeos(): View
    {
        return view('admin.seo.page-seos', [
            'title' => 'Dynamic Page SEO',
            'description' => 'Manage SEO templates for dynamic pages.',
            'records' => DynamicPageSeo::latest()->get(),
            'record' => new DynamicPageSeo(),
            'type' => 'dynamic',
            'routePrefix' => 'admin.dynamic-page-seos',
        ]);
    }

    public function storeDynamicPageSeo(Request $request): RedirectResponse
    {
        DynamicPageSeo::create($this->validatedSeoData($request, 'dynamic_page_seos'));

        return back()->with('status', 'Dynamic page SEO created.');
    }

    public function editDynamicPageSeo(DynamicPageSeo $dynamicPageSeo): View
    {
        return view('admin.seo.page-seos', [
            'title' => 'Dynamic Page SEO',
            'description' => 'Manage SEO templates for dynamic pages.',
            'records' => DynamicPageSeo::latest()->get(),
            'record' => $dynamicPageSeo,
            'type' => 'dynamic',
            'routePrefix' => 'admin.dynamic-page-seos',
        ]);
    }

    public function updateDynamicPageSeo(Request $request, DynamicPageSeo $dynamicPageSeo): RedirectResponse
    {
        $dynamicPageSeo->update($this->validatedSeoData($request, 'dynamic_page_seos', $dynamicPageSeo->id, $dynamicPageSeo->og_image_path));

        return redirect()->route('admin.dynamic-page-seos')->with('status', 'Dynamic page SEO updated.');
    }

    public function deleteDynamicPageSeo(DynamicPageSeo $dynamicPageSeo): RedirectResponse
    {
        $this->deletePublicFile($dynamicPageSeo->og_image_path);
        $dynamicPageSeo->delete();

        return back()->with('status', 'Dynamic page SEO deleted.');
    }

    public function defaultOgImages(): View
    {
        return view('admin.seo.default-og-images', [
            'records' => DefaultOgImage::latest()->get(),
            'record' => new DefaultOgImage(),
        ]);
    }

    public function storeDefaultOgImage(Request $request): RedirectResponse
    {
        DefaultOgImage::create($this->validatedDefaultOgImageData($request));

        return back()->with('status', 'Default OG image created.');
    }

    public function editDefaultOgImage(DefaultOgImage $defaultOgImage): View
    {
        return view('admin.seo.default-og-images', [
            'records' => DefaultOgImage::latest()->get(),
            'record' => $defaultOgImage,
        ]);
    }

    public function updateDefaultOgImage(Request $request, DefaultOgImage $defaultOgImage): RedirectResponse
    {
        $defaultOgImage->update($this->validatedDefaultOgImageData($request, $defaultOgImage->id, $defaultOgImage->file_path));

        return redirect()->route('admin.default-og-images')->with('status', 'Default OG image updated.');
    }

    public function deleteDefaultOgImage(DefaultOgImage $defaultOgImage): RedirectResponse
    {
        $this->deletePublicFile($defaultOgImage->file_path);
        $defaultOgImage->delete();

        return back()->with('status', 'Default OG image deleted.');
    }

    public function cdnSettings(): View
    {
        $cdnUrl = (string) SystemSetting::getSetting('cdn_url', '');

        return view('admin.system-settings.cdn', [
            'cdnEnabled' => (bool) SystemSetting::getSetting('cdn_enabled', false),
            'cdnUrl' => $cdnUrl,
            'cdnStatus' => $this->checkCdnStatus($cdnUrl),
        ]);
    }

    public function updateCdnSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cdn_enabled' => ['nullable', 'boolean'],
            'cdn_url' => ['nullable', 'string', 'max:255', 'regex:/^[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'],
        ], [
            'cdn_url.regex' => 'Please enter a valid CDN domain like cdn.example.com.',
        ]);

        SystemSetting::setSetting('cdn_enabled', $request->boolean('cdn_enabled'), 'boolean', 'Enable CDN asset URLs');
        SystemSetting::setSetting('cdn_url', $data['cdn_url'] ?? '', 'text', 'CDN domain for serving static assets');

        return back()->with('status', 'CDN settings updated.');
    }

    public function testCdn(): array
    {
        return $this->checkCdnStatus((string) SystemSetting::getSetting('cdn_url', ''));
    }

    public function emailSettings(): View
    {
        return view('admin.system-settings.email', [
            'emailMode' => SystemSetting::getSetting('email_mode', 'main'),
            'mainSettings' => $this->emailSettingsForMode('main'),
            'testingSettings' => $this->emailSettingsForMode('testing'),
        ]);
    }

    public function updateEmailMode(Request $request): RedirectResponse
    {
        $data = $request->validate(['email_mode' => ['required', 'in:main,testing']]);
        SystemSetting::setSetting('email_mode', $data['email_mode'], 'text', 'Email delivery mode');

        return back()->with('status', 'Email mode updated.');
    }

    public function updateMainEmailSettings(Request $request): RedirectResponse
    {
        $this->storeEmailSettings($request, 'main');

        return back()->with('status', 'Main email settings updated.');
    }

    public function updateTestingEmailSettings(Request $request): RedirectResponse
    {
        $this->storeEmailSettings($request, 'testing');

        return back()->with('status', 'Testing email settings updated.');
    }

    public function uploadFiles(): View
    {
        return view('admin.upload-files.index', [
            'files' => UploadFile::latest()->paginate(12),
        ]);
    }

    public function storeUploadFile(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,csv,txt,zip', 'max:10240'],
        ]);

        $path = $request->file('file')->store('files', 'public');

        UploadFile::create([
            'title' => $data['title'] ?: pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME),
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $path,
        ]);

        return back()->with('status', 'File uploaded.');
    }

    public function deleteUploadFile(UploadFile $uploadFile): RedirectResponse
    {
        $this->deletePublicFile($uploadFile->file_path);
        $uploadFile->delete();

        return back()->with('status', 'File deleted.');
    }

    public function leads(Request $request): View
    {
        $query = Lead::query();

        if ($request->filled('search')) {
            $search = (string) $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && in_array($request->query('status'), ['new', 'contacted', 'closed'], true)) {
            $query->where('status', $request->query('status'));
        }

        return view('admin.leads.index', [
            'leads' => $query->latest()->paginate(15)->withQueryString(),
        ]);
    }

    public function updateLeadStatus(Request $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validate([
            'status' => ['required', 'in:new,contacted,closed'],
        ]));

        return back()->with('status', 'Lead status updated.');
    }

    public function deleteLead(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('status', 'Lead deleted.');
    }

    public function forgotPassword(): View { return view('admin.forgot-password'); }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $email = strtolower(trim((string) $request->email));
        $user = User::where('email', $email)->where('role', 'admin')->first();

        if ($user) {
            $token = Password::broker()->createToken($user);
            $resetUrl = route('admin.reset-password', ['token' => $token, 'email' => $email]);

            Mail::html(
                view('emails.admin-password-reset', ['user' => $user, 'resetUrl' => $resetUrl])->render(),
                function ($message) use ($user) {
                    $message->to($user->email, $user->name)
                        ->subject('Reset your Zenotic Biotech admin password');
                }
            );
        }

        return back()->with('status', 'If an admin account exists for this email, a reset link has been sent.');
    }

    public function resetPassword(Request $request): View
    {
        return view('admin.reset-password', ['token' => $request->query('token'), 'email' => $request->query('email')]);
    }

    public function updateResetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email'], 'token' => ['required', 'string'], 'password' => ['required', 'confirmed', PasswordRule::min(8)]]);

        $status = Password::reset($data, function ($user, $password) {
            if ($user->role !== 'admin') {
                return;
            }

            $user->forceFill(['password' => Hash::make($password), 'remember_token' => Str::random(60)])->save();
        });

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('admin.login')->with('status', 'Password reset successfully. You can sign in now.')
            : back()->withErrors(['email' => 'This password reset link is invalid or expired.']);
    }

    private function validatedProductData(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'segment' => ['required', 'in:human,animal'],
            'status' => ['required', 'in:published,draft'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'image' => ['nullable', 'image', 'max:4096'],
            'features' => ['nullable', 'string'],
            'order_index' => ['nullable', 'integer'],
            'meta_title' => ['nullable', 'string'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'seo_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'best_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'review_number' => ['nullable', 'integer', 'min:0'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $data['image_url'] = '/storage/'.$request->file('image')->store('products', 'public');
        }

        $data['features'] = collect(preg_split('/\r\n|\r|\n/', (string) ($data['features'] ?? '')))->map(fn ($item) => trim($item))->filter()->values()->all();
        $data['order_index'] = $data['order_index'] ?? 0;

        if ($request->hasFile('og_image')) {
            $this->deletePublicFile($product?->og_image_path);
            $path = $request->file('og_image')->store('seo/product-og-images', 'public');
            $data['og_image_name'] = $request->file('og_image')->getClientOriginalName();
            $data['og_image_path'] = $path;
        }

        unset($data['image'], $data['og_image']);

        return $data;
    }

    private function validatedProductContentData(Request $request, Product $product, ?ProductContent $content = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:product_contents,id'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        if (! empty($data['parent_id'])) {
            $parent = ProductContent::where('product_id', $product->id)->whereKey($data['parent_id'])->first();
            abort_unless($parent && $parent->id !== $content?->id, 422);
        }

        $data['position'] = $data['position'] ?? ($product->contents()->count() + 1);

        return $data;
    }

    private function checkCdnStatus(string $cdnUrl): array
    {
        if ($cdnUrl === '') {
            return ['status' => 'inactive', 'message' => 'CDN not configured', 'reachable' => false];
        }

        $headers = @get_headers('https://'.rtrim($cdnUrl, '/').'/robots.txt', true);
        $ok = is_array($headers) && isset($headers[0]) && str_contains((string) $headers[0], '200');

        return [
            'status' => $ok ? 'active' : 'warning',
            'message' => $ok ? 'CDN is reachable.' : 'CDN is configured but could not be verified.',
            'reachable' => $ok,
        ];
    }

    private function emailSettingsForMode(string $mode): array
    {
        $defaults = [
            'main' => [
                'to_email' => 'info@zenoticbiotech.com',
                'to_name' => 'Zenotic Biotech',
                'cc_email' => 'info@zenoticbiotech.com',
                'cc_name' => 'Zenotic Biotech',
                'bcc_email' => 'info@zenoticbiotech.com',
                'bcc_name' => 'Zenotic Biotech',
            ],
            'testing' => [
                'to_email' => 'test@example.com',
                'to_name' => 'Test Email',
                'cc_email' => 'test@example.com',
                'cc_name' => 'Test CC',
                'bcc_email' => 'test@example.com',
                'bcc_name' => 'Test BCC',
            ],
        ];

        return collect($defaults[$mode])->mapWithKeys(
            fn ($value, $key) => [$key => SystemSetting::getSetting($mode.'_'.$key, $value)]
        )->all();
    }

    private function storeEmailSettings(Request $request, string $mode): void
    {
        $data = $request->validate([
            $mode.'_to_email' => ['required', 'email', 'max:255'],
            $mode.'_to_name' => ['required', 'string', 'max:255'],
            $mode.'_cc_email' => ['required', 'email', 'max:255'],
            $mode.'_cc_name' => ['required', 'string', 'max:255'],
            $mode.'_bcc_email' => ['required', 'email', 'max:255'],
            $mode.'_bcc_name' => ['required', 'string', 'max:255'],
        ]);

        foreach ($data as $key => $value) {
            SystemSetting::setSetting($key, $value, 'text', str_replace('_', ' ', $key));
        }
    }

    private function validatedServiceData(Request $request, ?Service $service = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'details' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:64'],
            'emoji' => ['nullable', 'string', 'max:64'],
            'status' => ['required', 'in:published,draft'],
            'order_index' => ['nullable', 'integer'],
            'meta_title' => ['nullable', 'string'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'seo_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'best_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'review_number' => ['nullable', 'integer', 'min:0'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        $data['icon'] = $data['icon'] ?: 'circle';
        $data['emoji'] = $data['emoji'] ?: '&#9679;';
        $data['order_index'] = $data['order_index'] ?? 0;

        if ($request->hasFile('og_image')) {
            $this->deletePublicFile($service?->og_image_path);
            $path = $request->file('og_image')->store('seo/service-og-images', 'public');
            $data['og_image_name'] = $request->file('og_image')->getClientOriginalName();
            $data['og_image_path'] = $path;
        }

        unset($data['og_image']);

        return $data;
    }

    private function validatedServiceContentData(Request $request, Service $service, ?ServiceContent $content = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:service_contents,id'],
            'position' => ['nullable', 'integer', 'min:1'],
        ]);

        if (! empty($data['parent_id'])) {
            $parent = ServiceContent::where('service_id', $service->id)->whereKey($data['parent_id'])->first();
            abort_unless($parent && $parent->id !== $content?->id, 422);
        }

        $data['position'] = $data['position'] ?? ($service->contents()->count() + 1);

        return $data;
    }

    private function validatedSeoData(Request $request, string $table, ?int $ignoreId = null, ?string $existingImage = null): array
    {
        $data = $request->validate([
            'page_name' => ['required', 'string', 'max:100', Rule::unique($table, 'page_name')->ignore($ignoreId)],
            'meta_title' => ['nullable', 'string'],
            'meta_keyword' => ['nullable', 'string'],
            'meta_description' => ['nullable', 'string'],
            'seo_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'best_rating' => ['nullable', 'numeric', 'min:1', 'max:5'],
            'review_number' => ['nullable', 'integer', 'min:0'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('og_image')) {
            $this->deletePublicFile($existingImage);
            $path = $request->file('og_image')->store('seo/og-images', 'public');
            $data['og_image_name'] = $request->file('og_image')->getClientOriginalName();
            $data['og_image_path'] = $path;
        }

        unset($data['og_image']);

        return $data;
    }

    private function validatedDefaultOgImageData(Request $request, ?int $ignoreId = null, ?string $existingImage = null): array
    {
        $data = $request->validate([
            'page' => ['required', 'string', 'max:200', Rule::unique('default_og_images', 'page')->ignore($ignoreId)],
            'og_image' => [$ignoreId ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
        ]);

        if ($request->hasFile('og_image')) {
            $this->deletePublicFile($existingImage);
            $path = $request->file('og_image')->store('seo/default-og-images', 'public');
            $data['file_name'] = $request->file('og_image')->getClientOriginalName();
            $data['file_path'] = $path;
        }

        $data['default'] = true;
        unset($data['og_image']);

        return $data;
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
