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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\partials\operation-history\history-item.htm */
class __TwigTemplate_431eba9a09ed9073a710eaa62dcd18b4 extends Template
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
        yield "<div id=\"product-list\">

    ";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["histories"] ?? null));
        $context['_iterated'] = false;
        foreach ($context['_seq'] as $context["_key"] => $context["history"]) {
            // line 4
            yield "
      <div class=\"operation-history__item table-item\">

        <div class=\"operation-history__checkbox\">
          <label for=\"\" class=\"checkbox\">
            <input type=\"checkbox\" class=\"product-check\"
              data-operation-id=\"";
            // line 10
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "operation_id", [], "any", false, false, true, 10), 10, $this->source), "html", null, true);
            yield "\"
              data-product-id=\"";
            // line 11
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product_id", [], "any", false, false, true, 11), 11, $this->source), "html", null, true);
            yield "\"
              data-name=\"";
            // line 12
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product_name", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
            yield "\"
              data-inv-number=\"";
            // line 13
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product_inv_number", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
            yield "\"
              data-unit=\"";
            // line 14
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product_unit", [], "any", false, false, true, 14), 14, $this->source), "html", null, true);
            yield "\"
              data-price=\"";
            // line 15
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product_price", [], "any", false, false, true, 15), 15, $this->source), "html", null, true);
            yield "\"
              data-quantity=\"";
            // line 16
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "quantity", [], "any", false, false, true, 16), 16, $this->source), "html", null, true);
            yield "\"
              data-sum=\"";
            // line 17
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "sum", [], "any", false, false, true, 17), 17, $this->source), "html", null, true);
            yield "\">
          </label>
        </div>

        <div class=\"operation-history__type label--type-operation\">
        ";
            // line 22
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["history"], "operation_type", [], "any", false, false, true, 22) == "Приход")) {
                // line 23
                yield "            <div class=\"label--type-operation__svg label--incoming\">
              <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
              </svg>
            </div>
        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 28
$context["history"], "operation_type", [], "any", false, false, true, 28) == "Списание")) {
                // line 29
                yield "            <div class=\"label--type-operation__svg label--write-off\">
                <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
                    <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
                </svg>
            </div>
        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 35
$context["history"], "operation_type", [], "any", false, false, true, 35) == "Передача")) {
                // line 36
                yield "            <div class=\"label--type-operation__svg label--outgoing\">
              <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
              </svg>
            </div>
        ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source,             // line 41
$context["history"], "operation_type", [], "any", false, false, true, 41) == "Импорт")) {
                // line 42
                yield "            <div class=\"label--type-operation__svg label--import\">
              <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M20.5 10.19H17.61C15.24 10.19 13.31 8.26 13.31 5.89V3C13.31 2.45 12.86 2 12.31 2H8.07C4.99 2 2.5 4 2.5 7.57V16.43C2.5 20 4.99 22 8.07 22H15.93C19.01 22 21.5 20 21.5 16.43V11.19C21.5 10.64 21.05 10.19 20.5 10.19Z\" fill=\"currentColor\"/>
                <path d=\"M15.8 2.21C15.39 1.8 14.68 2.08 14.68 2.65V6.14C14.68 7.6 15.92 8.81 17.43 8.81C18.38 8.82 19.7 8.82 20.83 8.82C21.4 8.82 21.7 8.15 21.3 7.75C19.86 6.3 17.28 3.69 15.8 2.21Z\" fill=\"currentColor\"/>
              </svg>
            </div>
        ";
            }
            // line 49
            yield "

        </div>

        <div class=\"operation-history__left\">
          <div class=\"operation-history__name\">
            ";
            // line 55
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product_name", [], "any", false, false, true, 55)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 56
                yield "                ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product_name", [], "any", false, false, true, 56), 56, $this->source), "html", null, true);
                yield "
            ";
            } else {
                // line 58
                yield "              -
            ";
            }
            // line 60
            yield "          </div>
          <div class=\"operation-history__number\">
            ";
            // line 62
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product", [], "any", false, false, true, 62), "inv_number", [], "any", false, false, true, 62)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 63
                yield "                ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product", [], "any", false, false, true, 63), "inv_number", [], "any", false, false, true, 63), 63, $this->source), "html", null, true);
                yield "
            ";
            } else {
                // line 65
                yield "              -
            ";
            }
            // line 67
            yield "          </div>
        </div>

        <div class=\"operation-history__price\">
          ";
            // line 71
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product", [], "any", false, false, true, 71), "price", [], "any", false, false, true, 71)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 72
                yield "              ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["history"], "product", [], "any", false, false, true, 72), "price", [], "any", false, false, true, 72), 72, $this->source), "html", null, true);
                yield "
          ";
            } else {
                // line 74
                yield "            -
          ";
            }
            // line 76
            yield "        </div>

        <div class=\"operation-history__quantity\">
          ";
            // line 79
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["history"], "quantity", [], "any", false, false, true, 79)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 80
                yield "              ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "quantity", [], "any", false, false, true, 80), 80, $this->source), "html", null, true);
                yield "
          ";
            } else {
                // line 82
                yield "            -
          ";
            }
            // line 84
            yield "        </div>

        <div class=\"operation-history__date\">
          ";
            // line 87
            yield ((CoreExtension::getAttribute($this->env, $this->source, $context["history"], "doc_date", [], "any", false, false, true, 87)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "doc_date", [], "any", false, false, true, 87), 87, $this->source), "html", null, true)) : ("-"));
            yield "
        </div>

        <div class=\"operation-history__counteragent\">
          ";
            // line 91
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["history"], "counteragent", [], "any", false, false, true, 91)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 92
                yield "              ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["history"], "counteragent", [], "any", false, false, true, 92), 92, $this->source), "html", null, true);
                yield "
          ";
            } else {
                // line 94
                yield "            -
          ";
            }
            // line 96
            yield "        </div>
      </div>

    ";
            $context['_iterated'] = true;
        }
        // line 99
        if (!$context['_iterated']) {
            // line 100
            yield "      <div class=\"operation-history__item\">Нет данных</div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['history'], $context['_parent'], $context['_iterated']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 102
        yield "</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\operation-history\\history-item.htm";
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
        return array (  247 => 102,  240 => 100,  238 => 99,  231 => 96,  227 => 94,  221 => 92,  219 => 91,  212 => 87,  207 => 84,  203 => 82,  197 => 80,  195 => 79,  190 => 76,  186 => 74,  180 => 72,  178 => 71,  172 => 67,  168 => 65,  162 => 63,  160 => 62,  156 => 60,  152 => 58,  146 => 56,  144 => 55,  136 => 49,  127 => 42,  125 => 41,  118 => 36,  116 => 35,  108 => 29,  106 => 28,  99 => 23,  97 => 22,  89 => 17,  85 => 16,  81 => 15,  77 => 14,  73 => 13,  69 => 12,  65 => 11,  61 => 10,  53 => 4,  48 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div id=\"product-list\">

    {% for history in histories %}

      <div class=\"operation-history__item table-item\">

        <div class=\"operation-history__checkbox\">
          <label for=\"\" class=\"checkbox\">
            <input type=\"checkbox\" class=\"product-check\"
              data-operation-id=\"{{ history.operation_id }}\"
              data-product-id=\"{{ history.product_id }}\"
              data-name=\"{{ history.product_name }}\"
              data-inv-number=\"{{ history.product_inv_number }}\"
              data-unit=\"{{ history.product_unit }}\"
              data-price=\"{{ history.product_price }}\"
              data-quantity=\"{{ history.quantity }}\"
              data-sum=\"{{ history.sum}}\">
          </label>
        </div>

        <div class=\"operation-history__type label--type-operation\">
        {% if history.operation_type == 'Приход' %}
            <div class=\"label--type-operation__svg label--incoming\">
              <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
              </svg>
            </div>
        {% elseif history.operation_type == 'Списание' %}
            <div class=\"label--type-operation__svg label--write-off\">
                <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
                    <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
                </svg>
            </div>
        {% elseif history.operation_type == 'Передача' %}
            <div class=\"label--type-operation__svg label--outgoing\">
              <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
              </svg>
            </div>
        {% elseif history.operation_type == 'Импорт' %}
            <div class=\"label--type-operation__svg label--import\">
              <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M20.5 10.19H17.61C15.24 10.19 13.31 8.26 13.31 5.89V3C13.31 2.45 12.86 2 12.31 2H8.07C4.99 2 2.5 4 2.5 7.57V16.43C2.5 20 4.99 22 8.07 22H15.93C19.01 22 21.5 20 21.5 16.43V11.19C21.5 10.64 21.05 10.19 20.5 10.19Z\" fill=\"currentColor\"/>
                <path d=\"M15.8 2.21C15.39 1.8 14.68 2.08 14.68 2.65V6.14C14.68 7.6 15.92 8.81 17.43 8.81C18.38 8.82 19.7 8.82 20.83 8.82C21.4 8.82 21.7 8.15 21.3 7.75C19.86 6.3 17.28 3.69 15.8 2.21Z\" fill=\"currentColor\"/>
              </svg>
            </div>
        {% endif %}


        </div>

        <div class=\"operation-history__left\">
          <div class=\"operation-history__name\">
            {% if history.product_name %}
                {{ history.product_name }}
            {% else %}
              -
            {% endif %}
          </div>
          <div class=\"operation-history__number\">
            {% if history.product.inv_number %}
                {{ history.product.inv_number }}
            {% else %}
              -
            {% endif %}
          </div>
        </div>

        <div class=\"operation-history__price\">
          {% if history.product.price %}
              {{ history.product.price }}
          {% else %}
            -
          {% endif %}
        </div>

        <div class=\"operation-history__quantity\">
          {% if history.quantity %}
              {{ history.quantity }}
          {% else %}
            -
          {% endif %}
        </div>

        <div class=\"operation-history__date\">
          {{ history.doc_date ?: '-' }}
        </div>

        <div class=\"operation-history__counteragent\">
          {% if history.counteragent %}
              {{ history.counteragent }}
          {% else %}
            -
          {% endif %}
        </div>
      </div>

    {% else %}
      <div class=\"operation-history__item\">Нет данных</div>
    {% endfor %}
</div>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\operation-history\\history-item.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 3, "if" => 22];
        static $filters = ["escape" => 10];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['for', 'if'],
                ['escape'],
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
