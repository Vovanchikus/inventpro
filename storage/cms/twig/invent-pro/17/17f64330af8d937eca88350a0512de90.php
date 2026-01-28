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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\pages\home.htm */
class __TwigTemplate_e62c08a7c6e4d8d71a17a270a4feb169 extends Template
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
        yield "Главная страница

";
        // line 3
        $context['__cms_component_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->componentFunction("notesList"        , $context['__cms_component_params']        );
        unset($context['__cms_component_params']);
        // line 4
        yield "
<section class=\"notes-block\">
\t<h2>Заметки</h2>
\t<div class=\"notes-grid\">
\t\t";
        // line 8
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["notes"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["note"]) {
            // line 9
            yield "\t\t\t<div class=\"note-card\">
\t\t\t\t<h3>";
            // line 10
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "title", [], "any", false, false, true, 10)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "title", [], "any", false, false, true, 10), 10, $this->source), "html", null, true)) : ("Без названия"));
            yield "</h3>
\t\t\t\t<div class=\"note-meta\">";
            // line 11
            yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["note"], "due_date", [], "any", false, false, true, 11)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(("Срок: " . $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "due_date", [], "any", false, false, true, 11), 11, $this->source)), "html", null, true)) : (""));
            yield " ";
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "human_status", [], "any", false, false, true, 11)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "human_status", [], "any", false, false, true, 11), 11, $this->source), "html", null, true)) : (""));
            yield "</div>
\t\t\t\t<p>";
            // line 12
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "description", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
            yield "</p>
\t\t\t\t";
            // line 13
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 13) && Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 13)))) {
                // line 14
                yield "\t\t\t\t\t<div class=\"note-products\">
\t\t\t\t\t\t<strong>Товары в заметке:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t";
                // line 17
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "products", [], "any", false, false, true, 17));
                foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                    // line 18
                    yield "\t\t\t\t\t\t\t<li>";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 18)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 18), 18, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 18), 18, $this->source), "html", null, true)));
                    yield " — ";
                    yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 18)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 18), 18, $this->source), "html", null, true)) : (((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "qty", [], "any", false, false, true, 18)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "qty", [], "any", false, false, true, 18), 18, $this->source), "html", null, true)) : (0))));
                    yield "</li>
\t\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['p'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 20
                yield "\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>
\t\t\t\t";
            }
            // line 23
            yield "\t\t\t\t";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 23) && Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 23)))) {
                // line 24
                yield "\t\t\t\t\t<div class=\"note-operations\">
\t\t\t\t\t\t<strong>Операции:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t";
                // line 27
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "operations", [], "any", false, false, true, 27));
                foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
                    // line 28
                    yield "\t\t\t\t\t\t\t<li class=\"op-item ";
                    if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 28)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                        yield "op-draft";
                    }
                    yield "\">
\t\t\t\t\t\t\t\t<span class=\"op-type\">";
                    // line 29
                    yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 29)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Черновик") : ("Финальная"));
                    yield "</span>
\t\t\t\t\t\t\t\t<span class=\"op-status\">";
                    // line 30
                    yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "is_draft", [], "any", false, false, true, 30)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Документ разработан") : ((((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "documents_count", [], "any", false, false, true, 30)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Документы есть") : (""))));
                    yield "</span>
\t\t\t\t\t\t\t\t<ul class=\"op-products\">
\t\t\t\t\t\t\t\t";
                    // line 32
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "products", [], "any", false, false, true, 32));
                    foreach ($context['_seq'] as $context["_key"] => $context["p"]) {
                        // line 33
                        yield "\t\t\t\t\t\t\t\t\t<li>";
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 33)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "name", [], "any", false, false, true, 33), 33, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "inv_number", [], "any", false, false, true, 33), 33, $this->source), "html", null, true)));
                        yield " — ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["p"], "quantity", [], "any", false, false, true, 33), 33, $this->source), "html", null, true);
                        yield "</li>
\t\t\t\t\t\t\t\t";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_key'], $context['p'], $context['_parent']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 35
                    yield "\t\t\t\t\t\t\t\t</ul>
\t\t\t\t\t\t\t</li>
\t\t\t\t\t\t";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['op'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 38
                yield "\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>
\t\t\t\t";
            }
            // line 41
            yield "\t\t\t\t<div class=\"note-actions\">
\t\t\t\t\t<a href=\"/add-note?note_id=";
            // line 42
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["note"], "id", [], "any", false, false, true, 42), 42, $this->source), "html", null, true);
            yield "\" class=\"btn\">Открыть</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t";
            $context['_iterated'] = true;
        }
        // line 45
        if (!$context['_iterated']) {
            // line 46
            yield "\t\t\t<p>Заметок пока нет.</p>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['note'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 48
        yield "\t</div>
</section>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\home.htm";
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
        return array (  183 => 48,  176 => 46,  174 => 45,  166 => 42,  163 => 41,  158 => 38,  150 => 35,  139 => 33,  135 => 32,  130 => 30,  126 => 29,  119 => 28,  115 => 27,  110 => 24,  107 => 23,  102 => 20,  91 => 18,  87 => 17,  82 => 14,  80 => 13,  76 => 12,  70 => 11,  66 => 10,  63 => 9,  58 => 8,  52 => 4,  48 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("Главная страница

{% component 'notesList' %}

<section class=\"notes-block\">
\t<h2>Заметки</h2>
\t<div class=\"notes-grid\">
\t\t{% for note in notes %}
\t\t\t<div class=\"note-card\">
\t\t\t\t<h3>{{ note.title ?: 'Без названия' }}</h3>
\t\t\t\t<div class=\"note-meta\">{{ note.due_date ? 'Срок: ' ~ note.due_date : '' }} {{ note.human_status ?: '' }}</div>
\t\t\t\t<p>{{ note.description }}</p>
\t\t\t\t{% if note.products and note.products|length %}
\t\t\t\t\t<div class=\"note-products\">
\t\t\t\t\t\t<strong>Товары в заметке:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t{% for p in note.products %}
\t\t\t\t\t\t\t<li>{{ p.name ?: p.inv_number }} — {{ p.quantity ?: (p.qty ?: 0) }}</li>
\t\t\t\t\t\t{% endfor %}
\t\t\t\t\t\t</ul>
\t\t\t\t\t</div>
\t\t\t\t{% endif %}
\t\t\t\t{% if note.operations and note.operations|length %}
\t\t\t\t\t<div class=\"note-operations\">
\t\t\t\t\t\t<strong>Операции:</strong>
\t\t\t\t\t\t<ul>
\t\t\t\t\t\t{% for op in note.operations %}
\t\t\t\t\t\t\t<li class=\"op-item {% if op.is_draft %}op-draft{% endif %}\">
\t\t\t\t\t\t\t\t<span class=\"op-type\">{{ op.is_draft ? 'Черновик' : 'Финальная' }}</span>
\t\t\t\t\t\t\t\t<span class=\"op-status\">{{ op.is_draft ? 'Документ разработан' : (op.documents_count ? 'Документы есть' : '') }}</span>
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
\t\t\t\t\t<a href=\"/add-note?note_id={{ note.id }}\" class=\"btn\">Открыть</a>
\t\t\t\t</div>
\t\t\t</div>
\t\t{% else %}
\t\t\t<p>Заметок пока нет.</p>
\t\t{% endfor %}
\t</div>
</section>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\home.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["component" => 3, "for" => 8, "if" => 13];
        static $filters = ["escape" => 10, "length" => 13];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['component', 'for', 'if'],
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
