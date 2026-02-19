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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\pages\notes.htm */
class __TwigTemplate_5a4bc0afae20139cf2520da0197690e0 extends Template
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
        yield "<section class=\"notes-page\">

    <div class=\"notes-page__top\">

        <div class=\"notes-page__header\">
            <h1 class=\"notes-page__title\">Нотатки</h1>
        </div>";
        // line 8
        yield "
        <div class=\"notes-filter\" data-notes-filter>
            <button type=\"button\" class=\"notes-filter__tab is-active\" data-filter=\"all\">
                <span class=\"notes-filter__label\">Все</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"in_development\">
                <span class=\"notes-filter__label\">В разработке</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"document_prepared\">
                <span class=\"notes-filter__label\">Документы готовы</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"in_accounting\">
                <span class=\"notes-filter__label\">В бухгалтерии</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"completed\">
                <span class=\"notes-filter__label\">Выполнено</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
        </div>";
        // line 31
        yield "
    </div>";
        // line 33
        yield "
    ";
        // line 34
        $context['__cms_partial_params'] = [];
        $context['__cms_partial_params']['notes'] = ($context["notes"] ?? null)        ;
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("notes/list"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 35
        yield "</section>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\notes.htm";
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
        return array (  87 => 35,  82 => 34,  79 => 33,  76 => 31,  52 => 8,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<section class=\"notes-page\">

    <div class=\"notes-page__top\">

        <div class=\"notes-page__header\">
            <h1 class=\"notes-page__title\">Нотатки</h1>
        </div>{# notes-page__header #}

        <div class=\"notes-filter\" data-notes-filter>
            <button type=\"button\" class=\"notes-filter__tab is-active\" data-filter=\"all\">
                <span class=\"notes-filter__label\">Все</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"in_development\">
                <span class=\"notes-filter__label\">В разработке</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"document_prepared\">
                <span class=\"notes-filter__label\">Документы готовы</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"in_accounting\">
                <span class=\"notes-filter__label\">В бухгалтерии</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
            <button type=\"button\" class=\"notes-filter__tab\" data-filter=\"completed\">
                <span class=\"notes-filter__label\">Выполнено</span>
                <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>0</span>
            </button>
        </div>{# notes-filter #}

    </div>{# notes-pages__top #}

    {% partial 'notes/list' notes=notes %}
</section>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\notes.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["partial" => 34];
        static $filters = [];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['partial'],
                [],
                [],
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
