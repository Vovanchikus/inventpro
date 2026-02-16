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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\pages\note-item.htm */
class __TwigTemplate_829ce775cddfc5eeb909e6c79d0da8c5 extends Template
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
    <div class=\"notes-page__header\">
        <h1 class=\"notes-page__title\">Нотатка</h1>
    </div>

    ";
        // line 6
        if ((($tmp = ($context["note"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 7
            yield "        ";
            $context['__cms_partial_params'] = [];
            $context['__cms_partial_params']['note'] = ($context["note"] ?? null)            ;
            echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("notes/item-single"            , $context['__cms_partial_params']            , true            );
            unset($context['__cms_partial_params']);
            // line 8
            yield "    ";
        } else {
            // line 9
            yield "        <div class=\"notes-empty\">Нотатку не знайдено.</div>
    ";
        }
        // line 11
        yield "</section>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\note-item.htm";
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
        return array (  66 => 11,  62 => 9,  59 => 8,  53 => 7,  51 => 6,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<section class=\"notes-page\">
    <div class=\"notes-page__header\">
        <h1 class=\"notes-page__title\">Нотатка</h1>
    </div>

    {% if note %}
        {% partial 'notes/item-single' note=note %}
    {% else %}
        <div class=\"notes-empty\">Нотатку не знайдено.</div>
    {% endif %}
</section>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\note-item.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 6, "partial" => 7];
        static $filters = [];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'partial'],
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
