<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* C:\OSPanel\domains\inventpro\themes\invent-pro\layouts\default.htm */
class __TwigTemplate_9c0636a85e94406f5f91bf2fd3e02328 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<!DOCTYPE html>
<html lang=\"ru\">
<head>
    <meta charset=\"utf-8\">
    <title>";
        // line 5
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 5), "title", [], "any", false, false, true, 5), 5, $this->source), "html", null, true);
        yield "</title>
    <meta name=\"description\" content=\"";
        // line 6
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 6), "meta_description", [], "any", false, false, true, 6), 6, $this->source), "html", null, true);
        yield "\">
    <meta name=\"title\" content=\"";
        // line 7
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 7), "meta_title", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
        yield "\">
    <meta name=\"author\" content=\"Winter CMS\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"generator\" content=\"Winter CMS\">
    ";
        // line 12
        yield "
    <!-- Стили -->

    <link rel=\"stylesheet\" href=\"";
        // line 15
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/lightgallery-bundle.min.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 16
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/OverlayScrollbars.min.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 17
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/swiper-bundle.min.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 18
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/normalize.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 19
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/variables.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 20
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/style.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 21
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/media.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">

    ";
        // line 23
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('css');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('styles');
        // line 24
        yield "</head>
<body>
    <div class=\"container\">

        ";
        // line 28
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/header"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 29
        yield "
        ";
        // line 30
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/sidebar"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 31
        yield "
        <main>
            ";
        // line 33
        echo $this->env->getExtension('Cms\Twig\Extension')->pageFunction();
        // line 34
        yield "        </main>

        ";
        // line 36
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/bottom-bar"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 37
        yield "

        ";
        // line 39
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("modals/modal"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 40
        yield "        <!-- Winter framework + Snowboard -->
        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>

        ";
        // line 43
        $_minify = System\Classes\CombineAssets::instance()->useMinify;
        if ($_minify) {
            echo '<script src="http://inventpro.loc/modules/system/assets/js/framework.combined-min.js?v=1.2.8"></script>'.PHP_EOL;
        }
        else {
            echo '<script src="http://inventpro.loc/modules/system/assets/js/framework.js?v=1.2.8"></script>'.PHP_EOL;
            echo '<script src="http://inventpro.loc/modules/system/assets/js/framework.extras.js?v=1.2.8"></script>'.PHP_EOL;
        }
        echo '<link rel="stylesheet" property="stylesheet" href="http://inventpro.loc/modules/system/assets/css/framework.extras'.($_minify ? '-min' : '').'.css?v=1.2.8">'.PHP_EOL;
        unset($_minify);
        // line 44
        yield "        ";
        $_minify = System\Classes\CombineAssets::instance()->useMinify;
        echo '<script data-module="snowboard-manifest" src="http://inventpro.loc/modules/system/assets/js/build/manifest.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-vendor" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.vendor.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-base" data-base-url="http://inventpro.loc/" data-asset-url="http://inventpro.loc/" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.base.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="request" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.request.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="attr" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.data-attr.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="extras" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.extras.js?v=1.2.8"></script>'.PHP_EOL;
        // line 45
        yield "
        <!-- Мои скрипты -->
        <script src=\"";
        // line 47
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/button-progress.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 48
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/modal.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 49
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/toast.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 50
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/handlers.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 51
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/header-search.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 52
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/import-excel.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 53
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/notes-modal.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 54
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/OverlayScrollbars.min.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 55
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/scripts.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>


        ";
        // line 58
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 58), "url", [], "any", false, false, true, 58) == "/")) {
            // line 59
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/home.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 61
        yield "
        ";
        // line 62
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 62), "url", [], "any", false, false, true, 62) == "/add-operation") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 62), "url", [], "any", false, false, true, 62) == "/edit-operation"))) {
            // line 63
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-form.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 65
        yield "
        ";
        // line 66
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 66), "url", [], "any", false, false, true, 66) == "/operation-history") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 66), "url", [], "any", false, false, true, 66) == "/documents"))) {
            // line 67
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-history.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 69
        yield "
        ";
        // line 70
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 70), "url", [], "any", false, false, true, 70) == "/notes") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 70), "url", [], "any", false, false, true, 70) == "/notes/:id"))) {
            // line 71
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/notes-tabs.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 73
        yield "
        ";
        // line 74
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 74), "url", [], "any", false, false, true, 74) == "/notes")) {
            // line 75
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/notes-filter.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 77
        yield "
        ";
        // line 78
        if ((((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 78), "url", [], "any", false, false, true, 78) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 78), "url", [], "any", false, false, true, 78) == "/documents/:slug")) || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 78), "url", [], "any", false, false, true, 78) == "/warehouse/:slug"))) {
            // line 79
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/copy-click.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 81
        yield "
        ";
        // line 82
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 82), "id", [], "any", false, false, true, 82) != "product-page")) {
            // line 83
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/table.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 85
        yield "
        ";
        // line 86
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 86), "id", [], "any", false, false, true, 86) == "product-page")) {
            // line 87
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/product-page.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 88
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/swiper-bundle.min.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 89
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/lightgallery.min.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 90
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/lg-zoom.min.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 92
        yield "
        ";
        // line 93
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 93), "url", [], "any", false, false, true, 93) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 93), "url", [], "any", false, false, true, 93) == "/operation-history"))) {
            // line 94
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/sidebar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 95
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/bottom-bar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 97
        yield "
        ";
        // line 98
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('js');
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('vite');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('scripts');
        // line 99
        yield "
    </div>
</body>
</html>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\layouts\\default.htm";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  385 => 99,  381 => 98,  378 => 97,  371 => 95,  364 => 94,  362 => 93,  359 => 92,  352 => 90,  346 => 89,  340 => 88,  333 => 87,  331 => 86,  328 => 85,  320 => 83,  318 => 82,  315 => 81,  307 => 79,  305 => 78,  302 => 77,  294 => 75,  292 => 74,  289 => 73,  281 => 71,  279 => 70,  276 => 69,  268 => 67,  266 => 66,  263 => 65,  255 => 63,  253 => 62,  250 => 61,  242 => 59,  240 => 58,  232 => 55,  226 => 54,  220 => 53,  214 => 52,  208 => 51,  202 => 50,  196 => 49,  190 => 48,  184 => 47,  180 => 45,  171 => 44,  160 => 43,  155 => 40,  151 => 39,  147 => 37,  143 => 36,  139 => 34,  137 => 33,  133 => 31,  129 => 30,  126 => 29,  122 => 28,  116 => 24,  113 => 23,  106 => 21,  100 => 20,  94 => 19,  88 => 18,  82 => 17,  76 => 16,  70 => 15,  65 => 12,  58 => 7,  54 => 6,  50 => 5,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<!DOCTYPE html>
<html lang=\"ru\">
<head>
    <meta charset=\"utf-8\">
    <title>{{ this.page.title }}</title>
    <meta name=\"description\" content=\"{{ this.page.meta_description }}\">
    <meta name=\"title\" content=\"{{ this.page.meta_title }}\">
    <meta name=\"author\" content=\"Winter CMS\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <meta name=\"generator\" content=\"Winter CMS\">
    {# <link rel=\"icon\" type=\"image/png\" href=\"{{ 'assets/img/winter.png'|theme }}\"> #}

    <!-- Стили -->

    <link rel=\"stylesheet\" href=\"{{ 'assets/css/lightgallery-bundle.min.css'|theme }}?v={{ random(100000,999999) }}\">
    <link rel=\"stylesheet\" href=\"{{ 'assets/css/OverlayScrollbars.min.css'|theme }}?v={{ random(100000,999999) }}\">
    <link rel=\"stylesheet\" href=\"{{ 'assets/css/swiper-bundle.min.css'|theme }}?v={{ random(100000,999999) }}\">
    <link rel=\"stylesheet\" href=\"{{ 'assets/css/normalize.css'|theme }}?v={{ random(100000,999999) }}\">
    <link rel=\"stylesheet\" href=\"{{ 'assets/css/variables.css'|theme }}?v={{ random(100000,999999) }}\">
    <link rel=\"stylesheet\" href=\"{{ 'assets/css/style.css'|theme }}?v={{ random(100000,999999) }}\">
    <link rel=\"stylesheet\" href=\"{{ 'assets/css/media.css'|theme }}?v={{ random(100000,999999) }}\">

    {% styles %}
</head>
<body>
    <div class=\"container\">

        {% partial 'global/header' %}

        {% partial 'global/sidebar' %}

        <main>
            {% page %}
        </main>

        {% partial 'global/bottom-bar' %}


        {% partial 'modals/modal' %}
        <!-- Winter framework + Snowboard -->
        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>

        {% framework extras %}
        {% snowboard all %}

        <!-- Мои скрипты -->
        <script src=\"{{ 'assets/js/core/button-progress.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/modal.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/toast.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/handlers.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/header-search.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/import-excel.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/pages/notes-modal.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/OverlayScrollbars.min.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/scripts.js'|theme }}?v={{ random(100000,999999) }}\"></script>


        {% if this.page.url == '/' %}
            <script src=\"{{ 'assets/js/pages/home.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/add-operation' or this.page.url == '/edit-operation' %}
            <script src=\"{{ 'assets/js/pages/operation-form.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/operation-history' or this.page.url == '/documents' %}
            <script src=\"{{ 'assets/js/pages/operation-history.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/notes' or this.page.url == '/notes/:id' %}
            <script src=\"{{ 'assets/js/pages/notes-tabs.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/notes' %}
            <script src=\"{{ 'assets/js/pages/notes-filter.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/warehouse' or this.page.url == '/documents/:slug' or this.page.url == '/warehouse/:slug' %}
            <script src=\"{{ 'assets/js/pages/copy-click.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.id != 'product-page' %}
            <script src=\"{{ 'assets/js/pages/table.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.id == 'product-page' %}
            <script src=\"{{ 'assets/js/pages/product-page.js'|theme }}?v={{ random(100000,999999) }}\"></script>
            <script src=\"{{ 'assets/js/swiper-bundle.min.js'|theme }}?v={{ random(100000,999999) }}\"></script>
            <script src=\"{{ 'assets/js/lightgallery.min.js'|theme }}?v={{ random(100000,999999) }}\"></script>
            <script src=\"{{ 'assets/js/lg-zoom.min.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/warehouse' or this.page.url == '/operation-history' %}
            <script src=\"{{ 'assets/js/pages/sidebar.js'|theme }}?v={{ random(100000,999999) }}\"></script>
            <script src=\"{{ 'assets/js/pages/bottom-bar.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% scripts %}

    </div>
</body>
</html>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\layouts\\default.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["styles" => 23, "partial" => 28, "page" => 33, "framework" => 43, "snowboard" => 44, "if" => 58, "scripts" => 98];
        static $filters = ["escape" => 5, "theme" => 15];
        static $functions = ["random" => 15];

        try {
            $this->sandbox->checkSecurity(
                ['styles', 'partial', 'page', 'framework', 'snowboard', 'if', 'scripts'],
                ['escape', 'theme'],
                ['random'],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
