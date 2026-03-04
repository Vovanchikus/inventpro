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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\layouts\default.htm */
class __TwigTemplate_f02584fb4927fc94a4249df2ef7e4c8e extends Template
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
        <script>
            window.jQuery || document.write('<script src=\"/modules/backend/assets/js/vendor/jquery.min.js\"><\\\\/script>');
        </script>

        ";
        // line 46
        $_minify = System\Classes\CombineAssets::instance()->useMinify;
        if ($_minify) {
            echo '<script src="http://inventpro-test/modules/system/assets/js/framework.combined-min.js?v=1.2.9"></script>'.PHP_EOL;
        }
        else {
            echo '<script src="http://inventpro-test/modules/system/assets/js/framework.js?v=1.2.9"></script>'.PHP_EOL;
            echo '<script src="http://inventpro-test/modules/system/assets/js/framework.extras.js?v=1.2.9"></script>'.PHP_EOL;
        }
        echo '<link rel="stylesheet" property="stylesheet" href="http://inventpro-test/modules/system/assets/css/framework.extras'.($_minify ? '-min' : '').'.css?v=1.2.9">'.PHP_EOL;
        unset($_minify);
        // line 47
        yield "        ";
        $_minify = System\Classes\CombineAssets::instance()->useMinify;
        echo '<script data-module="snowboard-manifest" src="http://inventpro-test/modules/system/assets/js/build/manifest.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-vendor" src="http://inventpro-test/modules/system/assets/js/snowboard/build/snowboard.vendor.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-base" data-base-url="http://inventpro-test/" data-asset-url="http://inventpro-test/" src="http://inventpro-test/modules/system/assets/js/snowboard/build/snowboard.base.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="request" src="http://inventpro-test/modules/system/assets/js/snowboard/build/snowboard.request.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="attr" src="http://inventpro-test/modules/system/assets/js/snowboard/build/snowboard.data-attr.js?v=1.2.9"></script>'.PHP_EOL;
        echo '<script data-module="extras" src="http://inventpro-test/modules/system/assets/js/snowboard/build/snowboard.extras.js?v=1.2.9"></script>'.PHP_EOL;
        // line 48
        yield "
        <!-- Мои скрипты -->
        <script src=\"";
        // line 50
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/button-progress.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 51
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/modal.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 52
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/toast.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 53
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/handlers.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 54
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/header-search.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 55
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/import-excel.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 56
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/notes-modal.js");
        yield "?v=";
        yield $this->env->getFilter('date')->getCallable()($this->env, "now", "U");
        yield "\"></script>
        <script src=\"";
        // line 57
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/OverlayScrollbars.min.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 58
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/scripts.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>


        ";
        // line 61
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 61), "url", [], "any", false, false, true, 61) == "/")) {
            // line 62
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/home.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 64
        yield "
        ";
        // line 65
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 65), "url", [], "any", false, false, true, 65) == "/add-operation") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 65), "url", [], "any", false, false, true, 65) == "/edit-operation"))) {
            // line 66
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-form.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 68
        yield "
        ";
        // line 69
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 69), "url", [], "any", false, false, true, 69) == "/operation-documents-builder")) {
            // line 70
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/doc-generation-builder.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 72
        yield "
        ";
        // line 73
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 73), "url", [], "any", false, false, true, 73) == "/settings")) {
            // line 74
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/settings.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 76
        yield "
        ";
        // line 77
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 77), "url", [], "any", false, false, true, 77) == "/operation-history") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 77), "url", [], "any", false, false, true, 77) == "/documents"))) {
            // line 78
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-history.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 80
        yield "
        ";
        // line 81
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 81), "url", [], "any", false, false, true, 81) == "/notes") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 81), "url", [], "any", false, false, true, 81) == "/notes/:id"))) {
            // line 82
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/notes-tabs.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 84
        yield "
        ";
        // line 85
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 85), "url", [], "any", false, false, true, 85) == "/notes")) {
            // line 86
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/notes-filter.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 88
        yield "
        ";
        // line 89
        if ((((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 89), "url", [], "any", false, false, true, 89) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 89), "url", [], "any", false, false, true, 89) == "/documents/:slug")) || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 89), "url", [], "any", false, false, true, 89) == "/warehouse/:slug"))) {
            // line 90
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/copy-click.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 92
        yield "
        ";
        // line 93
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 93), "id", [], "any", false, false, true, 93) != "product-page")) {
            // line 94
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/table.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 96
        yield "
        ";
        // line 97
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 97), "id", [], "any", false, false, true, 97) == "product-page")) {
            // line 98
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/product-page.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 99
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/swiper-bundle.min.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 100
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/lightgallery.min.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 101
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/lg-zoom.min.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 103
        yield "
        ";
        // line 104
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 104), "url", [], "any", false, false, true, 104) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 104), "url", [], "any", false, false, true, 104) == "/operation-history"))) {
            // line 105
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/sidebar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 106
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/bottom-bar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 108
        yield "
        ";
        // line 109
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('js');
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('vite');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('scripts');
        // line 110
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
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\layouts\\default.htm";
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
        return array (  414 => 110,  410 => 109,  407 => 108,  400 => 106,  393 => 105,  391 => 104,  388 => 103,  381 => 101,  375 => 100,  369 => 99,  362 => 98,  360 => 97,  357 => 96,  349 => 94,  347 => 93,  344 => 92,  336 => 90,  334 => 89,  331 => 88,  323 => 86,  321 => 85,  318 => 84,  310 => 82,  308 => 81,  305 => 80,  297 => 78,  295 => 77,  292 => 76,  284 => 74,  282 => 73,  279 => 72,  271 => 70,  269 => 69,  266 => 68,  258 => 66,  256 => 65,  253 => 64,  245 => 62,  243 => 61,  235 => 58,  229 => 57,  223 => 56,  217 => 55,  211 => 54,  205 => 53,  199 => 52,  193 => 51,  187 => 50,  183 => 48,  174 => 47,  163 => 46,  155 => 40,  151 => 39,  147 => 37,  143 => 36,  139 => 34,  137 => 33,  133 => 31,  129 => 30,  126 => 29,  122 => 28,  116 => 24,  113 => 23,  106 => 21,  100 => 20,  94 => 19,  88 => 18,  82 => 17,  76 => 16,  70 => 15,  65 => 12,  58 => 7,  54 => 6,  50 => 5,  44 => 1,);
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
        <script>
            window.jQuery || document.write('<script src=\"/modules/backend/assets/js/vendor/jquery.min.js\"><\\\\/script>');
        </script>

        {% framework extras %}
        {% snowboard all %}

        <!-- Мои скрипты -->
        <script src=\"{{ 'assets/js/core/button-progress.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/modal.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/toast.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/handlers.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/header-search.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/import-excel.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/pages/notes-modal.js'|theme }}?v={{ \"now\"|date(\"U\") }}\"></script>
        <script src=\"{{ 'assets/js/OverlayScrollbars.min.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        <script src=\"{{ 'assets/js/core/scripts.js'|theme }}?v={{ random(100000,999999) }}\"></script>


        {% if this.page.url == '/' %}
            <script src=\"{{ 'assets/js/pages/home.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/add-operation' or this.page.url == '/edit-operation' %}
            <script src=\"{{ 'assets/js/pages/operation-form.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/operation-documents-builder' %}
            <script src=\"{{ 'assets/js/pages/doc-generation-builder.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/settings' %}
            <script src=\"{{ 'assets/js/pages/settings.js'|theme }}?v={{ random(100000,999999) }}\"></script>
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
</html>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\layouts\\default.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["styles" => 23, "partial" => 28, "page" => 33, "framework" => 46, "snowboard" => 47, "if" => 61, "scripts" => 109];
        static $filters = ["escape" => 5, "theme" => 15, "date" => 56];
        static $functions = ["random" => 15];

        try {
            $this->sandbox->checkSecurity(
                ['styles', 'partial', 'page', 'framework', 'snowboard', 'if', 'scripts'],
                ['escape', 'theme', 'date'],
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
