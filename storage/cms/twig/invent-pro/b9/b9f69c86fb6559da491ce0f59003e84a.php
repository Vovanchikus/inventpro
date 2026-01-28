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
class __TwigTemplate_77919c17102347df68b987c4d3dc907b extends Template
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
        // line 14
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/normalize.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 15
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/variables.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 16
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/style.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">
    <link rel=\"stylesheet\" href=\"";
        // line 17
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/css/media.css");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\">

    ";
        // line 19
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('css');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('styles');
        // line 20
        yield "</head>
<body>
    <div class=\"container\">

        ";
        // line 24
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/header"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 25
        yield "
        ";
        // line 26
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/sidebar"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 27
        yield "
        <main>
            ";
        // line 29
        echo $this->env->getExtension('Cms\Twig\Extension')->pageFunction();
        // line 30
        yield "        </main>

        ";
        // line 32
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/bottom-bar"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 33
        yield "

        ";
        // line 35
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("modals/modal"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 36
        yield "        <!-- Winter framework + Snowboard -->
        <script src=\"https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js\"></script>

        ";
        // line 39
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
        // line 40
        yield "        ";
        $_minify = System\Classes\CombineAssets::instance()->useMinify;
        echo '<script data-module="snowboard-manifest" src="http://inventpro.loc/modules/system/assets/js/build/manifest.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-vendor" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.vendor.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="snowboard-base" data-base-url="http://inventpro.loc/" data-asset-url="http://inventpro.loc/" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.base.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="request" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.request.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="attr" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.data-attr.js?v=1.2.8"></script>'.PHP_EOL;
        echo '<script data-module="extras" src="http://inventpro.loc/modules/system/assets/js/snowboard/build/snowboard.extras.js?v=1.2.8"></script>'.PHP_EOL;
        // line 41
        yield "
        <!-- Мои скрипты -->
        <script src=\"";
        // line 43
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/button-progress.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 44
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/modal.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 45
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/toast.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 46
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/handlers.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 47
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/header-search.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>
        <script src=\"";
        // line 48
        yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/core/import-excel.js");
        yield "?v=";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
        yield "\"></script>

        ";
        // line 50
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 50), "url", [], "any", false, false, true, 50) == "/")) {
            // line 51
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/home.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 53
        yield "
        ";
        // line 54
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 54), "url", [], "any", false, false, true, 54) == "/add-operation") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 54), "url", [], "any", false, false, true, 54) == "/edit-operation"))) {
            // line 55
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-form.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 57
        yield "
        ";
        // line 58
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 58), "url", [], "any", false, false, true, 58) == "/operation-history")) {
            // line 59
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/operation-history.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 61
        yield "
        ";
        // line 62
        if ((((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 62), "url", [], "any", false, false, true, 62) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 62), "url", [], "any", false, false, true, 62) == "/documents/:slug")) || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 62), "url", [], "any", false, false, true, 62) == "/warehouse/:slug"))) {
            // line 63
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/copy-click.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 65
        yield "
        ";
        // line 66
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 66), "id", [], "any", false, false, true, 66) != "product-page")) {
            // line 67
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/table.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 68
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/product-page.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 70
        yield "
        ";
        // line 71
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 71), "id", [], "any", false, false, true, 71) == "product-page")) {
            // line 72
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/product-page.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 74
        yield "
        ";
        // line 75
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 75), "url", [], "any", false, false, true, 75) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 75), "url", [], "any", false, false, true, 75) == "/operation-history"))) {
            // line 76
            yield "            <script src=\"";
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/sidebar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
            <script src=\"";
            // line 77
            yield $this->extensions['Cms\Twig\Extension']->themeFilter("assets/js/pages/bottom-bar.js");
            yield "?v=";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::random($this->env->getCharset(), 100000, 999999), "html", null, true);
            yield "\"></script>
        ";
        }
        // line 79
        yield "
        ";
        // line 80
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('js');
        echo $this->env->getExtension('Cms\Twig\Extension')->assetsFunction('vite');
        echo $this->env->getExtension('Cms\Twig\Extension')->displayBlock('scripts');
        // line 81
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
        return array (  309 => 81,  305 => 80,  302 => 79,  295 => 77,  288 => 76,  286 => 75,  283 => 74,  275 => 72,  273 => 71,  270 => 70,  263 => 68,  256 => 67,  254 => 66,  251 => 65,  243 => 63,  241 => 62,  238 => 61,  230 => 59,  228 => 58,  225 => 57,  217 => 55,  215 => 54,  212 => 53,  204 => 51,  202 => 50,  195 => 48,  189 => 47,  183 => 46,  177 => 45,  171 => 44,  165 => 43,  161 => 41,  152 => 40,  141 => 39,  136 => 36,  132 => 35,  128 => 33,  124 => 32,  120 => 30,  118 => 29,  114 => 27,  110 => 26,  107 => 25,  103 => 24,  97 => 20,  94 => 19,  87 => 17,  81 => 16,  75 => 15,  69 => 14,  65 => 12,  58 => 7,  54 => 6,  50 => 5,  44 => 1,);
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
        static $tags = ["styles" => 19, "partial" => 24, "page" => 29, "framework" => 39, "snowboard" => 40, "if" => 50, "scripts" => 80];
        static $filters = ["escape" => 5, "theme" => 14];
        static $functions = ["random" => 14];

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
