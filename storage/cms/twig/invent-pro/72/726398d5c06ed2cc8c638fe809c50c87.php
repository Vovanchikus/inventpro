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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\notes_grid.htm */
class __TwigTemplate_92781a330bc9fd45bf8dc62a016773b8 extends Template
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
        yield "<div class=\"notes-grid\">
\t";
        // line 2
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["notes"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["note"]) {
            // line 3
            yield "\t\t<div class=\"note-card\">
\t\t\t<h3>";
            // line 4
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "title", [], "any", false, false, true, 4)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "title", [], "any", false, false, true, 4), 4, $this->source), "html", null, true)) : ("Без названия"));
            yield "</h3>
\t\t\t<div class=\"note-meta\">";
            // line 5
            yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["note"], "due_date", [], "any", false, false, true, 5)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("Срок: " . $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "due_date", [], "any", false, false, true, 5), 5, $this->source)), "html", null, true)) : (""));
            yield " ";
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "human_status", [], "any", false, false, true, 5)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "human_status", [], "any", false, false, true, 5), 5, $this->source), "html", null, true)) : (""));
            yield "</div>
\t\t\t<p>";
            // line 6
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "description", [], "any", false, false, true, 6), 6, $this->source), "html", null, true);
            yield "</p>
\t\t\t";
            // line 7
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 7) && Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 7)))) {
                // line 8
                yield "\t\t\t\t<div class=\"note-products\">
\t\t\t\t\t<strong>Товары в заметке:</strong>
\t\t\t\t\t<ul>
\t\t\t\t\t";
                // line 11
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 11));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 12
                    yield "\t\t\t\t\t\t<li>";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 12)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 12), 12, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 12), 12, $this->source), "html", null, true)));
                    yield " — ";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 12)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 12), 12, $this->source), "html", null, true)) : (((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "qty", [], "any", false, false, true, 12)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "qty", [], "any", false, false, true, 12), 12, $this->source), "html", null, true)) : (0))));
                    yield "</li>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['p'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 14
                yield "\t\t\t\t\t</ul>
\t\t\t\t</div>
\t\t\t";
            }
            // line 17
            yield "\t\t\t";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 17) && Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 17)))) {
                // line 18
                yield "\t\t\t\t<div class=\"note-operations\">
\t\t\t\t\t<strong>Операции:</strong>
\t\t\t\t\t<ul>
\t\t\t\t\t";
                // line 21
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 21));
                foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
                    // line 22
                    yield "\t\t\t\t\t\t<li class=\"op-item ";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 22)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        yield "op-draft";
                    }
                    yield "\">
\t\t\t\t\t\t\t<span class=\"op-type\">";
                    // line 23
                    yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 23)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Черновик") : ("Финальная"));
                    yield "</span>
\t\t\t\t\t\t\t<span class=\"op-status\">";
                    // line 24
                    yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 24)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Документ разработан") : ((((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "documents_count", [], "any", false, false, true, 24)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Документы есть") : (""))));
                    yield "</span>
\t\t\t\t\t\t\t<ul class=\"op-products\">
\t\t\t\t\t\t\t";
                    // line 26
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "products", [], "any", false, false, true, 26));
                    foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                        // line 27
                        yield "\t\t\t\t\t\t\t\t<li>";
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 27)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 27), 27, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 27), 27, $this->source), "html", null, true)));
                        yield " — ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
                        yield "</li>
\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_key'], $context['p'], $context['_parent']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 29
                    yield "\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t</li>
\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['op'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 32
                yield "\t\t\t\t\t</ul>
\t\t\t\t</div>
\t\t\t";
            }
            // line 35
            yield "\t\t\t<div class=\"note-actions\">
\t\t\t\t<a href=\"/add-note?note_id=";
            // line 36
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "id", [], "any", false, false, true, 36), 36, $this->source), "html", null, true);
            yield "\" class=\"btn\">Открыть</a>
\t\t\t</div>
\t\t</div>
\t";
            $context['_iterated'] = true;
        }
        // line 39
        if (!$context['_iterated']) {
            // line 40
            yield "\t\t<p>Заметок пока нет.</p>
\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['note'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        yield "</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\notes_grid.htm";
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
        return array (  172 => 42,  165 => 40,  163 => 39,  155 => 36,  152 => 35,  147 => 32,  139 => 29,  128 => 27,  124 => 26,  119 => 24,  115 => 23,  108 => 22,  104 => 21,  99 => 18,  96 => 17,  91 => 14,  80 => 12,  76 => 11,  71 => 8,  69 => 7,  65 => 6,  59 => 5,  55 => 4,  52 => 3,  47 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"notes-grid\">
\t{% for note in notes %}
\t\t<div class=\"note-card\">
\t\t\t<h3>{{ note.title ?: 'Без названия' }}</h3>
\t\t\t<div class=\"note-meta\">{{ note.due_date ? 'Срок: ' ~ note.due_date : '' }} {{ note.human_status ?: '' }}</div>
\t\t\t<p>{{ note.description }}</p>
\t\t\t{% if note.products and note.products|length %}
\t\t\t\t<div class=\"note-products\">
\t\t\t\t\t<strong>Товары в заметке:</strong>
\t\t\t\t\t<ul>
\t\t\t\t\t{% for p in note.products %}
\t\t\t\t\t\t<li>{{ p.name ?: p.inv_number }} — {{ p.quantity ?: (p.qty ?: 0) }}</li>
\t\t\t\t\t{% endfor %}
\t\t\t\t\t</ul>
\t\t\t\t</div>
\t\t\t{% endif %}
\t\t\t{% if note.operations and note.operations|length %}
\t\t\t\t<div class=\"note-operations\">
\t\t\t\t\t<strong>Операции:</strong>
\t\t\t\t\t<ul>
\t\t\t\t\t{% for op in note.operations %}
\t\t\t\t\t\t<li class=\"op-item {% if op.is_draft %}op-draft{% endif %}\">
\t\t\t\t\t\t\t<span class=\"op-type\">{{ op.is_draft ? 'Черновик' : 'Финальная' }}</span>
\t\t\t\t\t\t\t<span class=\"op-status\">{{ op.is_draft ? 'Документ разработан' : (op.documents_count ? 'Документы есть' : '') }}</span>
\t\t\t\t\t\t\t<ul class=\"op-products\">
\t\t\t\t\t\t\t{% for p in op.products %}
\t\t\t\t\t\t\t\t<li>{{ p.name ?: p.inv_number }} — {{ p.quantity }}</li>
\t\t\t\t\t\t\t{% endfor %}
\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t</li>
\t\t\t\t\t{% endfor %}
\t\t\t\t\t</ul>
\t\t\t\t</div>
\t\t\t{% endif %}
\t\t\t<div class=\"note-actions\">
\t\t\t\t<a href=\"/add-note?note_id={{ note.id }}\" class=\"btn\">Открыть</a>
\t\t\t</div>
\t\t</div>
\t{% else %}
\t\t<p>Заметок пока нет.</p>
\t{% endfor %}
</div>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\notes_grid.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 2, "if" => 7];
        static $filters = ["escape" => 4, "length" => 7];
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
