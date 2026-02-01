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
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/overlayscrollbars/css/OverlayScrollbars.min.css\" />
    <link rel=\"stylesheet\" href=\"";
        // line 15
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/normalize.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 16
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/variables.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 17
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/style.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 18
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/media.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">

    ";
        // line 20
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('css');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('styles');
        // line 21
        yield "</head>
<body>
    <div class=\"container\">

        ";
        // line 25
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/header"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 26
        yield "
        ";
        // line 27
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/sidebar"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 28
        yield "
        <main>
            ";
        // line 30
        echo $this->env->getExtension('Cms\Twig\Extension')->pageFunction();
        // line 31
        yield "        </main>

        ";
        // line 33
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/bottom-bar"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 34
        yield "

        ";
        // line 36
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("modals/modal"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 37
        yield "        <!-- Winter framework + Snowboard -->
        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>

        ";
        // line 40
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
        // line 41
        yield "        ";
        $_minify = System\Classes\CombineAssets::instance()->useMinify;
        echo '<script data-module="snowboard-manifest" src="http://inventpro.loc/modules/system/assets/js/build/manifest.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-vendor" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.vendor.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-base" data-base-url="http://inventpro.loc/" data-asset-url="http://inventpro.loc/" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.base.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="request" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.request.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="attr" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.data-attr.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="extras" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.extras.js?v=1.2.8"></script>'.PHP_EOL;
        // line 42
        yield "
        <!-- Мои скрипты -->
        <script src=\"";
        // line 44
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/button-progress.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 45
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/modal.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 46
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/toast.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 47
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/handlers.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 48
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/header-search.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 49
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/import-excel.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 50
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/notes-modal.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"https://cdn.jsdelivr.net/npm/overlayscrollbars/js/OverlayScrollbars.min.js\"></script>
        <script src=\"";
        // line 52
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/scripts.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>


        ";
        // line 55
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 55), "url", [], "any", false, false, true, 55) == "/")) {
            // line 56
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/home.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 58
        yield "
        ";
        // line 59
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 59), "url", [], "any", false, false, true, 59) == "/add-operation") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 59), "url", [], "any", false, false, true, 59) == "/edit-operation"))) {
            // line 60
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-form.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 62
        yield "
        ";
        // line 63
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 63), "url", [], "any", false, false, true, 63) == "/operation-history")) {
            // line 64
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-history.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 66
        yield "
        ";
        // line 67
        if ((((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 67), "url", [], "any", false, false, true, 67) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 67), "url", [], "any", false, false, true, 67) == "/documents/:slug")) || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 67), "url", [], "any", false, false, true, 67) == "/warehouse/:slug"))) {
            // line 68
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/copy-click.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 70
        yield "
        ";
        // line 71
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 71), "id", [], "any", false, false, true, 71) != "product-page")) {
            // line 72
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/table.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 73
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/product-page.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 75
        yield "
        ";
        // line 76
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 76), "id", [], "any", false, false, true, 76) == "product-page")) {
            // line 77
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/product-page.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 79
        yield "
        ";
        // line 80
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 80), "url", [], "any", false, false, true, 80) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 80), "url", [], "any", false, false, true, 80) == "/operation-history"))) {
            // line 81
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/sidebar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 82
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/bottom-bar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 84
        yield "
        ";
        // line 85
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('js');
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('vite');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('scripts');
        // line 86
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
        return array (  324 => 86,  320 => 85,  317 => 84,  310 => 82,  303 => 81,  301 => 80,  298 => 79,  290 => 77,  288 => 76,  285 => 75,  278 => 73,  271 => 72,  269 => 71,  266 => 70,  258 => 68,  256 => 67,  253 => 66,  245 => 64,  243 => 63,  240 => 62,  232 => 60,  230 => 59,  227 => 58,  219 => 56,  217 => 55,  209 => 52,  202 => 50,  196 => 49,  190 => 48,  184 => 47,  178 => 46,  172 => 45,  166 => 44,  162 => 42,  153 => 41,  142 => 40,  137 => 37,  133 => 36,  129 => 34,  125 => 33,  121 => 31,  119 => 30,  115 => 28,  111 => 27,  108 => 26,  104 => 25,  98 => 21,  95 => 20,  88 => 18,  82 => 17,  76 => 16,  70 => 15,  65 => 12,  58 => 7,  54 => 6,  50 => 5,  44 => 1,);
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
    <link rel=\"stylesheet\" href=\"https://cdn.jsdelivr.net/npm/overlayscrollbars/css/OverlayScrollbars.min.css\" />
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
        <script src=\"https://cdn.jsdelivr.net/npm/overlayscrollbars/js/OverlayScrollbars.min.js\"></script>
        <script src=\"{{ 'assets/js/core/scripts.js'|theme }}?v={{ random(100000,999999) }}\"></script>


        {% if this.page.url == '/' %}
            <script src=\"{{ 'assets/js/pages/home.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/add-operation' or this.page.url == '/edit-operation' %}
            <script src=\"{{ 'assets/js/pages/operation-form.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/operation-history' %}
            <script src=\"{{ 'assets/js/pages/operation-history.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.url == '/warehouse' or this.page.url == '/documents/:slug' or this.page.url == '/warehouse/:slug' %}
            <script src=\"{{ 'assets/js/pages/copy-click.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.id != 'product-page' %}
            <script src=\"{{ 'assets/js/pages/table.js'|theme }}?v={{ random(100000,999999) }}\"></script>
            <script src=\"{{ 'assets/js/pages/product-page.js'|theme }}?v={{ random(100000,999999) }}\"></script>
        {% endif %}

        {% if this.page.id == 'product-page' %}
            <script src=\"{{ 'assets/js/pages/product-page.js'|theme }}?v={{ random(100000,999999) }}\"></script>
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
        static $tags = ["styles" => 20, "partial" => 25, "page" => 30, "framework" => 40, "snowboard" => 41, "if" => 55, "scripts" => 85];
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
