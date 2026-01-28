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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\workflow\note.htm */
class __TwigTemplate_b552f810ae031a71831ab82876ade59c extends Template
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
        yield "<div class=\"workflow-note ";
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "is_completed", [], "any", false, false, true, 1)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            yield "is-completed";
        }
        yield "\">
    <div class=\"note-header\">
        <h3>";
        // line 3
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "title", [], "any", false, false, true, 3), 3, $this->source), "html", null, true);
        yield "</h3>

        ";
        // line 5
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "deadline_at", [], "any", false, false, true, 5)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 6
            yield "            <div class=\"note-deadline\">
                Срок: ";
            // line 7
            yield $this->env->getFilter('date')->getCallable()($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "deadline_at", [], "any", false, false, true, 7), 7, $this->source), "d.m.Y");
            yield "
            </div>
        ";
        }
        // line 10
        yield "
        <div class=\"note-status\">
            Статус:
            <span class=\"status ";
        // line 13
        yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "is_completed", [], "any", false, false, true, 13)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("done") : ("work"));
        yield "\">
                ";
        // line 14
        yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "is_completed", [], "any", false, false, true, 14)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("Завершена") : ("В работе"));
        yield "
            </span>
        </div>
    </div>

    <div class=\"note-items\">
        <strong>Товары:</strong>

        ";
        // line 22
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "items", [], "any", false, false, true, 22));
        foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
            // line 23
            yield "            <div class=\"note-item\">
                <span>";
            // line 24
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "product", [], "any", false, true, true, 24), "name", [], "any", true, true, true, 24) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "product", [], "any", false, false, true, 24), "name", [], "any", false, false, true, 24)))) ? (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["item"], "product", [], "any", false, false, true, 24), "name", [], "any", false, false, true, 24)) : ("Товар #")) . $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "product_id", [], "any", false, false, true, 24), 24, $this->source)), "html", null, true);
            yield "</span>
                <span class=\"item-status ";
            // line 25
            yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "is_completed", [], "any", false, false, true, 25)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("done") : ("work"));
            yield "\">
                    ";
            // line 26
            yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["item"], "is_completed", [], "any", false, false, true, 26)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("✓ Готово") : ("В работе"));
            yield "
                </span>
            </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 30
        yield "    </div>

    <div class=\"note-operations\">
        <strong>Операции:</strong>

        ";
        // line 35
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "operations", [], "any", false, false, true, 35))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 36
            yield "            <ul>
                ";
            // line 37
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "operations", [], "any", false, false, true, 37));
            foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
                // line 38
                yield "                    <li>
                        ";
                // line 39
                yield (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["op"], "type", [], "any", false, true, true, 39), "name", [], "any", true, true, true, 39) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["op"], "type", [], "any", false, false, true, 39), "name", [], "any", false, false, true, 39)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["op"], "type", [], "any", false, false, true, 39), "name", [], "any", false, false, true, 39), 39, $this->source), "html", null, true)) : ("Операция"));
                yield "
                        — ";
                // line 40
                yield $this->env->getFilter('date')->getCallable()($this->env, $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "created_at", [], "any", false, false, true, 40), 40, $this->source), "d.m.Y H:i");
                yield "
                    </li>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['op'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 43
            yield "            </ul>
        ";
        } else {
            // line 45
            yield "            <em>Операций пока нет</em>
        ";
        }
        // line 47
        yield "
</n+        <div class=\"note-actions\">
                    <button class=\"addOperationForNotes btn btn-sm btn-add-operation\"
                        data-note-id=\"";
        // line 50
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "id", [], "any", false, false, true, 50), 50, $this->source), "html", null, true);
        yield "\"
                        data-request=\"onShowAddOperationModal\"
                        data-request-data=\"note_id: ";
        // line 52
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "id", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
        yield "\"
                        data-request-flash>
                            Добавить операцию
                    </button>
</div>
    </div>
</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\workflow\\note.htm";
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
        return array (  163 => 52,  158 => 50,  153 => 47,  149 => 45,  145 => 43,  136 => 40,  132 => 39,  129 => 38,  125 => 37,  122 => 36,  120 => 35,  113 => 30,  103 => 26,  99 => 25,  95 => 24,  92 => 23,  88 => 22,  77 => 14,  73 => 13,  68 => 10,  62 => 7,  59 => 6,  57 => 5,  52 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"workflow-note {% if note.is_completed %}is-completed{% endif %}\">
    <div class=\"note-header\">
        <h3>{{ note.title }}</h3>

        {% if note.deadline_at %}
            <div class=\"note-deadline\">
                Срок: {{ note.deadline_at|date('d.m.Y') }}
            </div>
        {% endif %}

        <div class=\"note-status\">
            Статус:
            <span class=\"status {{ note.is_completed ? 'done' : 'work' }}\">
                {{ note.is_completed ? 'Завершена' : 'В работе' }}
            </span>
        </div>
    </div>

    <div class=\"note-items\">
        <strong>Товары:</strong>

        {% for item in note.items %}
            <div class=\"note-item\">
                <span>{{ item.product.name ?? 'Товар #' ~ item.product_id }}</span>
                <span class=\"item-status {{ item.is_completed ? 'done' : 'work' }}\">
                    {{ item.is_completed ? '✓ Готово' : 'В работе' }}
                </span>
            </div>
        {% endfor %}
    </div>

    <div class=\"note-operations\">
        <strong>Операции:</strong>

        {% if note.operations|length %}
            <ul>
                {% for op in note.operations %}
                    <li>
                        {{ op.type.name ?? 'Операция' }}
                        — {{ op.created_at|date('d.m.Y H:i') }}
                    </li>
                {% endfor %}
            </ul>
        {% else %}
            <em>Операций пока нет</em>
        {% endif %}

</n+        <div class=\"note-actions\">
                    <button class=\"addOperationForNotes btn btn-sm btn-add-operation\"
                        data-note-id=\"{{ note.id }}\"
                        data-request=\"onShowAddOperationModal\"
                        data-request-data=\"note_id: {{ note.id }}\"
                        data-request-flash>
                            Добавить операцию
                    </button>
</div>
    </div>
</div>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\workflow\\note.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1, "for" => 22];
        static $filters = ["escape" => 3, "date" => 7, "length" => 35];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['escape', 'date', 'length'],
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
