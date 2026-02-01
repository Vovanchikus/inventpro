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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\pages\notes.htm */
class __TwigTemplate_61006ca3941225f3525950a5eee123be extends Template
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
        if ((($tmp = ($context["user"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 2
            yield "    <p>Hello ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "name", [], "any", false, false, true, 2), 2, $this->source), "html", null, true);
            yield "</p>
\t\t";
            // line 3
            $context["g"] = Twig\Extension\CoreExtension::first($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "groups", [], "any", false, false, true, 3), 3, $this->source));
            // line 4
            yield "\t\t";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["g"] ?? null), "name", [], "any", false, false, true, 4), 4, $this->source), "html", null, true);
            yield "
\t\t<a href=\"nojavascript...;\" data-request=\"onLogout\" data-request-data=\"redirect: '/'\">Sign out</a>
";
        } else {
            // line 7
            yield "    <p>Nobody is logged in</p>
";
        }
        // line 9
        yield "
";
        // line 11
        yield "
<section class=\"notes-block\">
\t<h2>Нотатки</h2>
\t<div class=\"notes-grid\">
\t\t";
        // line 15
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["notes"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["note"]) {
            // line 16
            yield "\t\t\t<div class=\"note-card\">
\t\t\t\t<h3>";
            // line 17
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "title", [], "any", false, false, true, 17)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "title", [], "any", false, false, true, 17), 17, $this->source), "html", null, true)) : ("Без названия"));
            yield "</h3>
\t\t\t\t<div class=\"note-meta\">";
            // line 18
            yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["note"], "due_date", [], "any", false, false, true, 18)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("Срок: " . $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "due_date", [], "any", false, false, true, 18), 18, $this->source)), "html", null, true)) : (""));
            yield " ";
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "human_status", [], "any", false, false, true, 18)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "human_status", [], "any", false, false, true, 18), 18, $this->source), "html", null, true)) : (""));
            yield "</div>
\t\t\t\t<p>";
            // line 19
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "description", [], "any", false, false, true, 19), 19, $this->source), "html", null, true);
            yield "</p>
\t\t\t\t";
            // line 20
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 20) && Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 20)))) {
                // line 21
                yield "\t\t\t\t\t<div class=\"note-products\">
\t\t\t\t\t\t<strong>Товари в нотатці:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t";
                // line 24
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 24));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 25
                    yield "\t\t\t\t\t\t\t<li>";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 25)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 25), 25, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 25), 25, $this->source), "html", null, true)));
                    yield " — ";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 25)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 25), 25, $this->source), "html", null, true)) : (((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "qty", [], "any", false, false, true, 25)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "qty", [], "any", false, false, true, 25), 25, $this->source), "html", null, true)) : (0))));
                    yield "</li>
\t\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['p'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 27
                yield "\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>
\t\t\t\t";
            }
            // line 30
            yield "\t\t\t\t";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 30) && Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 30)))) {
                // line 31
                yield "\t\t\t\t\t<div class=\"note-operations\">
\t\t\t\t\t\t<strong>Операції:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t";
                // line 34
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 34));
                foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
                    // line 35
                    yield "\t\t\t\t\t\t\t<li class=\"op-item ";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 35)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        yield "op-draft";
                    }
                    yield "\">
\t\t\t\t\t\t\t\t<span class=\"op-type\">";
                    // line 36
                    yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 36)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Черновик") : ("Фінальна"));
                    yield "</span>
\t\t\t\t\t\t\t\t<span class=\"op-status\">";
                    // line 37
                    yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 37)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Документ розроблено") : ((((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "documents_count", [], "any", false, false, true, 37)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Документи є") : (""))));
                    yield "</span>
\t\t\t\t\t\t\t\t<ul class=\"op-products\">
\t\t\t\t\t\t\t\t";
                    // line 39
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "products", [], "any", false, false, true, 39));
                    foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                        // line 40
                        yield "\t\t\t\t\t\t\t\t\t<li>";
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 40)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 40), 40, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 40), 40, $this->source), "html", null, true)));
                        yield " — ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 40), 40, $this->source), "html", null, true);
                        yield "</li>
\t\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_key'], $context['p'], $context['_parent']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 42
                    yield "\t\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['op'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 45
                yield "\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>
\t\t\t\t";
            }
            // line 48
            yield "\t\t\t\t<div class=\"note-actions\">
\t\t\t\t\t<a href=\"/add-note?note_id=";
            // line 49
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "id", [], "any", false, false, true, 49), 49, $this->source), "html", null, true);
            yield "\" class=\"btn\">Відкрити</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t";
            $context['_iterated'] = true;
        }
        // line 52
        if (!$context['_iterated']) {
            // line 53
            yield "\t\t\t<p>Нотаток поки немає.</p>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['note'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 55
        yield "\t</div>
</section>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\notes.htm";
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
        return array (  198 => 55,  191 => 53,  189 => 52,  181 => 49,  178 => 48,  173 => 45,  165 => 42,  154 => 40,  150 => 39,  145 => 37,  141 => 36,  134 => 35,  130 => 34,  125 => 31,  122 => 30,  117 => 27,  106 => 25,  102 => 24,  97 => 21,  95 => 20,  91 => 19,  85 => 18,  81 => 17,  78 => 16,  73 => 15,  67 => 11,  64 => 9,  60 => 7,  53 => 4,  51 => 3,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if user %}
    <p>Hello {{ user.name }}</p>
\t\t{% set g = user.groups|first %}
\t\t{{ g.name }}
\t\t<a href=\"nojavascript...;\" data-request=\"onLogout\" data-request-data=\"redirect: '/'\">Sign out</a>
{% else %}
    <p>Nobody is logged in</p>
{% endif %}

{# Заметки #}

<section class=\"notes-block\">
\t<h2>Нотатки</h2>
\t<div class=\"notes-grid\">
\t\t{% for note in notes %}
\t\t\t<div class=\"note-card\">
\t\t\t\t<h3>{{ note.title ?: 'Без названия' }}</h3>
\t\t\t\t<div class=\"note-meta\">{{ note.due_date ? 'Срок: ' ~ note.due_date : '' }} {{ note.human_status ?: '' }}</div>
\t\t\t\t<p>{{ note.description }}</p>
\t\t\t\t{% if note.products and note.products|length %}
\t\t\t\t\t<div class=\"note-products\">
\t\t\t\t\t\t<strong>Товари в нотатці:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t{% for p in note.products %}
\t\t\t\t\t\t\t<li>{{ p.name ?: p.inv_number }} — {{ p.quantity ?: (p.qty ?: 0) }}</li>
\t\t\t\t\t\t{% endfor %}
\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>
\t\t\t\t{% endif %}
\t\t\t\t{% if note.operations and note.operations|length %}
\t\t\t\t\t<div class=\"note-operations\">
\t\t\t\t\t\t<strong>Операції:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t{% for op in note.operations %}
\t\t\t\t\t\t\t<li class=\"op-item {% if op.is_draft %}op-draft{% endif %}\">
\t\t\t\t\t\t\t\t<span class=\"op-type\">{{ op.is_draft ? 'Черновик' : 'Фінальна' }}</span>
\t\t\t\t\t\t\t\t<span class=\"op-status\">{{ op.is_draft ? 'Документ розроблено' : (op.documents_count ? 'Документи є' : '') }}</span>
\t\t\t\t\t\t\t\t<ul class=\"op-products\">
\t\t\t\t\t\t\t\t{% for p in op.products %}
\t\t\t\t\t\t\t\t\t<li>{{ p.name ?: p.inv_number }} — {{ p.quantity }}</li>
\t\t\t\t\t\t\t\t{% endfor %}
\t\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t{% endfor %}
\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>
\t\t\t\t{% endif %}
\t\t\t\t<div class=\"note-actions\">
\t\t\t\t\t<a href=\"/add-note?note_id={{ note.id }}\" class=\"btn\">Відкрити</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t{% else %}
\t\t\t<p>Нотаток поки немає.</p>
\t\t{% endfor %}
\t</div>
</section>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\notes.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1, "set" => 3, "for" => 15];
        static $filters = ["escape" => 2, "first" => 3, "length" => 20];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set', 'for'],
                ['escape', 'first', 'length'],
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
