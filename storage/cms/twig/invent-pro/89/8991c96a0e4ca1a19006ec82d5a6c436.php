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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\notes\list.htm */
class __TwigTemplate_d2203b90488fa9bcb3f5832185467485 extends Template
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
        yield "<div class=\"notes-list\" data-notes-list>
    ";
        // line 2
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["notes"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["note"]) {
            // line 3
            yield "        ";
            $context['__cms_partial_params'] = [];
            $context['__cms_partial_params']['note'] = $context["note"]            ;
            echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("notes/item"            , $context['__cms_partial_params']            , true            );
            unset($context['__cms_partial_params']);
            // line 4
            yield "    ";
            $context['_iterated'] = true;
        }
        if (!$context['_iterated']) {
            // line 5
            yield "        <div class=\"notes-empty\">Нотаток поки немає.</div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['note'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 7
        yield "</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\notes\\list.htm";
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
        return array (  70 => 7,  63 => 5,  58 => 4,  52 => 3,  47 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"notes-list\" data-notes-list>
    {% for note in notes %}
        {% partial 'notes/item' note=note %}
    {% else %}
        <div class=\"notes-empty\">Нотаток поки немає.</div>
    {% endfor %}
</div>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\notes\\list.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 2, "partial" => 3];
        static $filters = [];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['for', 'partial'],
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
