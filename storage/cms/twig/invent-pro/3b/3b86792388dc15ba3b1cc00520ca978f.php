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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\partials\modals\modal_notes_list.htm */
class __TwigTemplate_b4d2c25d00dcf979dbfae47c7d64b8bb extends Template
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
        yield "<div class=\"notes-list\">
    ";
        // line 2
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["notes"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["n"]) {
            // line 3
            yield "        <div class=\"note-card\" data-id=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["n"], "id", [], "any", false, false, true, 3), 3, $this->source), "html", null, true);
            yield "\">
            <h4>";
            // line 4
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["n"], "title", [], "any", false, false, true, 4)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["n"], "title", [], "any", false, false, true, 4), 4, $this->source), "html", null, true)) : ("Без названия"));
            yield "</h4>
            <div class=\"note-meta\">";
            // line 5
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["n"], "due_date", [], "any", false, false, true, 5)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield "Срок: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["n"], "due_date", [], "any", false, false, true, 5), 5, $this->source), "html", null, true);
            }
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["n"], "status", [], "any", false, false, true, 5)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield " | ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["n"], "status", [], "any", false, false, true, 5), 5, $this->source), "html", null, true);
            }
            yield "</div>
            <p>";
            // line 6
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["n"], "description", [], "any", false, false, true, 6), 6, $this->source), "html", null, true);
            yield "</p>

            ";
            // line 8
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["n"], "products", [], "any", false, false, true, 8) && Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["n"], "products", [], "any", false, false, true, 8)))) {
                // line 9
                yield "                <ul class=\"note-products\">
                    ";
                // line 10
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["n"], "products", [], "any", false, false, true, 10));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 11
                    yield "                        <li>
                            ";
                    // line 12
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 12)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        yield "<span class=\"note-prod-inv\">";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
                        yield "</span>";
                    }
                    // line 13
                    yield "                            <span class=\"note-prod-name\">";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
                    yield "</span>
                            ";
                    // line 14
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 14)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        yield "<span class=\"note-prod-qty\"> x";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 14), 14, $this->source), "html", null, true);
                        yield "</span>";
                    }
                    // line 15
                    yield "                        </li>
                    ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['p'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 17
                yield "                </ul>
            ";
            }
            // line 19
            yield "
            <div class=\"note-actions\">
                <button class=\"btn btn-add-to-note\">Добавить выбранные</button>
            </div>
        </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['n'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 25
        yield "</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\modals\\modal_notes_list.htm";
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
        return array (  127 => 25,  116 => 19,  112 => 17,  105 => 15,  99 => 14,  94 => 13,  88 => 12,  85 => 11,  81 => 10,  78 => 9,  76 => 8,  71 => 6,  60 => 5,  56 => 4,  51 => 3,  47 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"notes-list\">
    {% for n in notes %}
        <div class=\"note-card\" data-id=\"{{ n.id }}\">
            <h4>{{ n.title ?: 'Без названия' }}</h4>
            <div class=\"note-meta\">{% if n.due_date %}Срок: {{ n.due_date }}{% endif %}{% if n.status %} | {{ n.status }}{% endif %}</div>
            <p>{{ n.description }}</p>

            {% if n.products and n.products|length %}
                <ul class=\"note-products\">
                    {% for p in n.products %}
                        <li>
                            {% if p.inv_number %}<span class=\"note-prod-inv\">{{ p.inv_number }}</span>{% endif %}
                            <span class=\"note-prod-name\">{{ p.name }}</span>
                            {% if p.quantity %}<span class=\"note-prod-qty\"> x{{ p.quantity }}</span>{% endif %}
                        </li>
                    {% endfor %}
                </ul>
            {% endif %}

            <div class=\"note-actions\">
                <button class=\"btn btn-add-to-note\">Добавить выбранные</button>
            </div>
        </div>
    {% endfor %}
</div>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\modals\\modal_notes_list.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 2, "if" => 5];
        static $filters = ["escape" => 3, "length" => 8];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['escape', 'length'],
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
