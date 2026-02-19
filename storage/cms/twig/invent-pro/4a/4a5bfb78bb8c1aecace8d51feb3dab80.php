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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\pages\documents.htm */
class __TwigTemplate_7b332261e7348076cfc4dcbffb07f63a extends Template
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
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/main-box"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 2
        yield "

";
        // line 4
        $context["status"] = (((array_key_exists("status", $context) &&  !(null === $context["status"]))) ? ($context["status"]) : ((((CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["status"], "method", true, true, true, 4) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["status"], "method", false, false, true, 4)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["status"], "method", false, false, true, 4)) : ("final"))));
        // line 5
        yield "
<div class=\"documents-status\">
  <a href=\"/documents?status=final\" class=\"documents-status__tab ";
        // line 7
        if ((($context["status"] ?? null) != "draft")) {
            yield "active";
        }
        yield "\">
    Готовые <span class=\"documents-status__count\">";
        // line 8
        yield (((array_key_exists("finalCount", $context) &&  !(null === $context["finalCount"]))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["finalCount"], 8, $this->source), "html", null, true)) : (0));
        yield "</span>
  </a>
  <a href=\"/documents?status=draft\" class=\"documents-status__tab ";
        // line 10
        if ((($context["status"] ?? null) == "draft")) {
            yield "active";
        }
        yield "\">
    В работе <span class=\"documents-status__count\">";
        // line 11
        yield (((array_key_exists("draftCount", $context) &&  !(null === $context["draftCount"]))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["draftCount"], 11, $this->source), "html", null, true)) : (0));
        yield "</span>
  </a>
</div>


<div class=\"documents-list\">

";
        // line 18
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["operations"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["operation"]) {
            // line 19
            yield "
    <div class=\"documents-item\">

    ";
            // line 22
            if ((($context["status"] ?? null) == "draft")) {
                // line 23
                yield "      <div class=\"documents-item__status\">
        <svg width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path d=\"M22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 5.81294 5.81294 1 11.75 1C17.6871 1 22.5 5.81294 22.5 11.75Z\" fill=\"currentColor\"/>
        </svg>
        <span>Черновик</span>
      </div>
    ";
            }
            // line 30
            yield "
      <div class=\"documents-item__top\">

        <div class=\"documents-item__top-left\">

          <div class=\"documents-item__type label--type-operation\">

            ";
            // line 37
            if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "type", [], "any", false, false, true, 37), "name", [], "any", false, false, true, 37) == "Приход")) {
                // line 38
                yield "              <div class=\"label--type-operation__svg label--incoming\">
                <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                  <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
                </svg>
              </div>
            ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 43
$context["operation"], "type", [], "any", false, false, true, 43), "name", [], "any", false, false, true, 43) == "Списание")) {
                // line 44
                yield "                <div class=\"label--type-operation__svg label--write-off\">
                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
                    <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
                  </svg>
                </div>
            ";
            } elseif ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source,             // line 50
$context["operation"], "type", [], "any", false, false, true, 50), "name", [], "any", false, false, true, 50) == "Передача")) {
                // line 51
                yield "                <div class=\"label--type-operation__svg label--outgoing\">
                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
                  </svg>
                </div>
            ";
            }
            // line 57
            yield "
          </div>";
            // line 59
            yield "
          <div class=\"documents-item__top-info\">

            <div class=\"documents-item__date\">
              ";
            // line 63
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "card_document_date", [], "any", false, false, true, 63)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 64
                yield "
                <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                  <path d=\"M6.25 13.25C6.25 12.6977 6.69772 12.25 7.25 12.25C7.80228 12.25 8.2501 12.6977 8.2501 13.25C8.2501 13.8023 7.80238 14.25 7.2501 14.25C6.69782 14.25 6.25 13.8023 6.25 13.25Z\" fill=\"currentColor\"/>
                  <path d=\"M11.75 12.25C11.1977 12.25 10.75 12.6977 10.75 13.25C10.75 13.8023 11.1977 14.25 11.75 14.25C12.3023 14.25 12.7501 13.8023 12.7501 13.25C12.7501 12.6977 12.3023 12.25 11.75 12.25Z\" fill=\"currentColor\"/>
                  <path d=\"M15.25 13.25C15.25 12.6977 15.6977 12.25 16.25 12.25C16.8023 12.25 17.2501 12.6977 17.2501 13.25C17.2501 13.8023 16.8024 14.25 16.2501 14.25C15.6978 14.25 15.25 13.8023 15.25 13.25Z\" fill=\"currentColor\"/>
                  <path d=\"M7.25 16.25C6.69772 16.25 6.25 16.6977 6.25 17.25C6.25 17.8023 6.69772 18.25 7.25 18.25C7.80228 18.25 8.2501 17.8023 8.2501 17.25C8.2501 16.6977 7.80228 16.25 7.25 16.25Z\" fill=\"currentColor\"/>
                  <path d=\"M10.75 17.25C10.75 16.6977 11.1977 16.25 11.75 16.25C12.3023 16.25 12.7501 16.6977 12.7501 17.25C12.7501 17.8023 12.3024 18.25 11.7501 18.25C11.1978 18.25 10.75 17.8023 10.75 17.25Z\" fill=\"currentColor\"/>
                  <path d=\"M16.25 16.25C15.6977 16.25 15.25 16.6977 15.25 17.25C15.25 17.8023 15.6977 18.25 16.25 18.25C16.8023 18.25 17.2501 17.8023 17.2501 17.25C17.2501 16.6977 16.8023 16.25 16.25 16.25Z\" fill=\"currentColor\"/>
                  <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M8.5 0.75C8.5 0.335786 8.16421 0 7.75 0C7.33579 0 7 0.335786 7 0.75V2.00007C6.19395 2.00064 5.53458 2.00569 4.99013 2.05018C4.36012 2.10165 3.81824 2.20963 3.32054 2.46322C2.52085 2.87068 1.87068 3.52085 1.46322 4.32054C1.20963 4.81824 1.10165 5.36012 1.05018 5.99013C0.999989 6.60439 0.999994 7.36493 1 8.31737V17.1826C0.999994 18.135 0.999989 18.8956 1.05018 19.5099C1.10165 20.1399 1.20963 20.6818 1.46322 21.1795C1.87068 21.9791 2.52085 22.6293 3.32054 23.0368C3.81824 23.2904 4.36012 23.3983 4.99013 23.4498C5.60438 23.5 6.3649 23.5 7.3173 23.5H16.1826C17.135 23.5 17.8956 23.5 18.5099 23.4498C19.1399 23.3983 19.6818 23.2904 20.1795 23.0368C20.9791 22.6293 21.6293 21.9791 22.0368 21.1795C22.2904 20.6818 22.3983 20.1399 22.4498 19.5099C22.5 18.8956 22.5 18.1351 22.5 17.1827V8.31737C22.5 7.36496 22.5 6.60438 22.4498 5.99013C22.3983 5.36012 22.2904 4.81824 22.0368 4.32054C21.6293 3.52085 20.9791 2.87068 20.1795 2.46322C19.6818 2.20963 19.1399 2.10165 18.5099 2.05018C17.9654 2.00569 17.3061 2.00064 16.5 2.00007V0.75C16.5 0.335786 16.1642 0 15.75 0C15.3358 0 15 0.335786 15 0.75V2H8.5V0.75ZM16.15 3.5C17.1425 3.5 17.8417 3.50058 18.3877 3.54519C18.925 3.58909 19.2475 3.67184 19.4985 3.79973C20.0159 4.06338 20.4366 4.48408 20.7003 5.00153C20.8282 5.25252 20.9109 5.57503 20.9548 6.11228C20.9855 6.4874 20.9953 6.93484 20.9985 7.5H2.5015C2.50468 6.93484 2.51455 6.4874 2.54519 6.11228C2.58909 5.57503 2.67184 5.25252 2.79973 5.00153C3.06338 4.48408 3.48408 4.06338 4.00153 3.79973C4.25252 3.67184 4.57503 3.58909 5.11228 3.54519C5.65829 3.50058 6.35753 3.5 7.35 3.5H16.15ZM2.5 9V17.15C2.5 18.1425 2.50058 18.8417 2.54519 19.3877C2.58909 19.925 2.67184 20.2475 2.79973 20.4985C3.06338 21.0159 3.48408 21.4366 4.00153 21.7003C4.25252 21.8282 4.57503 21.9109 5.11228 21.9548C5.65829 21.9994 6.35753 22 7.35 22H16.15C17.1425 22 17.8417 21.9994 18.3877 21.9548C18.925 21.9109 19.2475 21.8282 19.4985 21.7003C20.0159 21.4366 20.4366 21.0159 20.7003 20.4985C20.8282 20.2475 20.9109 19.925 20.9548 19.3877C20.9994 18.8417 21 18.1425 21 17.15V9H2.5Z\" fill=\"currentColor\"/>
                </svg>
                ";
                // line 74
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "card_document_date", [], "any", false, false, true, 74), 74, $this->source), "html", null, true);
                yield "
              ";
            }
            // line 76
            yield "            </div>";
            // line 77
            yield "
            <div class=\"documents-item__counteragent\">
              ";
            // line 79
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "first_counteragent", [], "any", false, false, true, 79), 79, $this->source), "html", null, true);
            yield "
            </div>

          </div>";
            // line 83
            yield "
        </div>";
            // line 85
            yield "
        <div class=\"documents-item__number\">
          ";
            // line 87
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "card_document_number", [], "any", false, false, true, 87)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield " № ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "card_document_number", [], "any", false, false, true, 87), 87, $this->source), "html", null, true);
            }
            // line 88
            yield "        </div>";
            // line 89
            yield "
      </div>";
            // line 91
            yield "
      <div class=\"documents-item__middle\">

        <div class=\"documents-item__purpose\">
          Підстава:
            ";
            // line 96
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "card_document_purpose", [], "any", false, false, true, 96)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 97
                yield "              <p>";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "card_document_purpose", [], "any", false, false, true, 97), 97, $this->source), "html", null, true);
                yield "</p>
          ";
            } else {
                // line 99
                yield "            <p>-</p>
          ";
            }
            // line 101
            yield "        </div>

      </div>";
            // line 104
            yield "
      <div class=\"documents-item__bottom\">

        ";
            // line 107
            if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "items_count", [], "any", false, false, true, 107)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 108
                yield "        <div class=\"documents-item__count\">
            ";
                // line 109
                $context["count"] = CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "items_count", [], "any", false, false, true, 109);
                // line 110
                yield "          ";
                $context["n"] = (($context["count"] ?? null) % 100);
                // line 111
                yield "          ";
                $context["n1"] = (($context["n"] ?? null) % 10);
                // line 112
                yield "
          ";
                // line 113
                if (((($context["n"] ?? null) > 10) && (($context["n"] ?? null) < 20))) {
                    // line 114
                    yield "              ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["count"] ?? null), 114, $this->source), "html", null, true);
                    yield " товаров
          ";
                } elseif ((                // line 115
($context["n1"] ?? null) == 1)) {
                    // line 116
                    yield "              ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["count"] ?? null), 116, $this->source), "html", null, true);
                    yield " товар
          ";
                } elseif (((                // line 117
($context["n1"] ?? null) >= 2) && (($context["n1"] ?? null) <= 4))) {
                    // line 118
                    yield "              ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["count"] ?? null), 118, $this->source), "html", null, true);
                    yield " товара
          ";
                } else {
                    // line 120
                    yield "              ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["count"] ?? null), 120, $this->source), "html", null, true);
                    yield " товаров
          ";
                }
                // line 122
                yield "
        </div>
        ";
            }
            // line 125
            yield "        ";
            if ((($context["status"] ?? null) == "draft")) {
                // line 126
                yield "          <button
            type=\"button\"
            class=\"button button--nm button--secondary js-delete-draft\"
            data-operation-id=\"";
                // line 129
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "id", [], "any", false, false, true, 129), 129, $this->source), "html", null, true);
                yield "\"
          >
            Видалити
          </button>
          <a href=\"/add-operation?operation_id=";
                // line 133
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "id", [], "any", false, false, true, 133), 133, $this->source), "html", null, true);
                yield "\" class=\"button button--nm button--brand\">Продовжити</a>
        ";
            } else {
                // line 135
                yield "          <a href=\"/documents/";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["operation"], "slug", [], "any", false, false, true, 135), 135, $this->source), "html", null, true);
                yield "\" class=\"button button--nm button--brand\">Детальніше</a>
        ";
            }
            // line 137
            yield "
      </div>";
            // line 139
            yield "
    </div>";
            // line 141
            yield "
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['operation'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 143
        yield "</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\documents.htm";
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
        return array (  316 => 143,  309 => 141,  306 => 139,  303 => 137,  297 => 135,  292 => 133,  285 => 129,  280 => 126,  277 => 125,  272 => 122,  266 => 120,  260 => 118,  258 => 117,  253 => 116,  251 => 115,  246 => 114,  244 => 113,  241 => 112,  238 => 111,  235 => 110,  233 => 109,  230 => 108,  228 => 107,  223 => 104,  219 => 101,  215 => 99,  209 => 97,  207 => 96,  200 => 91,  197 => 89,  195 => 88,  190 => 87,  186 => 85,  183 => 83,  177 => 79,  173 => 77,  171 => 76,  166 => 74,  154 => 64,  152 => 63,  146 => 59,  143 => 57,  135 => 51,  133 => 50,  125 => 44,  123 => 43,  116 => 38,  114 => 37,  105 => 30,  96 => 23,  94 => 22,  89 => 19,  85 => 18,  75 => 11,  69 => 10,  64 => 8,  58 => 7,  54 => 5,  52 => 4,  48 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% partial 'global/main-box' %}


{% set status = status ?? request.get('status') ?? 'final' %}

<div class=\"documents-status\">
  <a href=\"/documents?status=final\" class=\"documents-status__tab {% if status != 'draft' %}active{% endif %}\">
    Готовые <span class=\"documents-status__count\">{{ finalCount ?? 0 }}</span>
  </a>
  <a href=\"/documents?status=draft\" class=\"documents-status__tab {% if status == 'draft' %}active{% endif %}\">
    В работе <span class=\"documents-status__count\">{{ draftCount ?? 0 }}</span>
  </a>
</div>


<div class=\"documents-list\">

{% for operation in operations %}

    <div class=\"documents-item\">

    {% if status == 'draft' %}
      <div class=\"documents-item__status\">
        <svg width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path d=\"M22.5 11.75C22.5 17.6871 17.6871 22.5 11.75 22.5C5.81294 22.5 1 17.6871 1 11.75C1 5.81294 5.81294 1 11.75 1C17.6871 1 22.5 5.81294 22.5 11.75Z\" fill=\"currentColor\"/>
        </svg>
        <span>Черновик</span>
      </div>
    {% endif %}

      <div class=\"documents-item__top\">

        <div class=\"documents-item__top-left\">

          <div class=\"documents-item__type label--type-operation\">

            {% if operation.type.name == 'Приход' %}
              <div class=\"label--type-operation__svg label--incoming\">
                <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                  <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
                </svg>
              </div>
            {% elseif operation.type.name == 'Списание' %}
                <div class=\"label--type-operation__svg label--write-off\">
                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
                    <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
                  </svg>
                </div>
            {% elseif operation.type.name == 'Передача' %}
                <div class=\"label--type-operation__svg label--outgoing\">
                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M17.2197 15.7197C17.5126 15.4268 17.9874 15.4268 18.2803 15.7197C18.5732 16.0126 18.5732 16.4874 18.2803 16.7803L13.7803 21.2803C13.4874 21.5732 13.0126 21.5732 12.7197 21.2803L8.21967 16.7803C7.92678 16.4874 7.92678 16.0126 8.21967 15.7197C8.51256 15.4268 8.98744 15.4268 9.28033 15.7197L12.5 18.9393V8.35C12.5 7.35753 12.4994 6.65829 12.4548 6.11228C12.4109 5.57503 12.3282 5.25252 12.2003 5.00153C11.9366 4.48408 11.5159 4.06339 10.9985 3.79973C10.7475 3.67184 10.425 3.58909 9.88772 3.54519C9.34171 3.50058 8.64247 3.5 7.65 3.5H5.75C5.33579 3.5 5 3.16421 5 2.75C5 2.33579 5.33579 2 5.75 2H7.68261C8.63503 1.99999 9.39562 1.99999 10.0099 2.05018C10.6399 2.10165 11.1818 2.20963 11.6795 2.46322C12.4791 2.87068 13.1293 3.52085 13.5368 4.32054C13.7904 4.81824 13.8984 5.36012 13.9498 5.99013C14 6.60439 14 7.36493 14 8.31737V18.9393L17.2197 15.7197Z\" fill=\"currentColor\"/>
                  </svg>
                </div>
            {% endif %}

          </div>{# documents-item__type label--icon #}

          <div class=\"documents-item__top-info\">

            <div class=\"documents-item__date\">
              {% if operation.card_document_date %}

                <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                  <path d=\"M6.25 13.25C6.25 12.6977 6.69772 12.25 7.25 12.25C7.80228 12.25 8.2501 12.6977 8.2501 13.25C8.2501 13.8023 7.80238 14.25 7.2501 14.25C6.69782 14.25 6.25 13.8023 6.25 13.25Z\" fill=\"currentColor\"/>
                  <path d=\"M11.75 12.25C11.1977 12.25 10.75 12.6977 10.75 13.25C10.75 13.8023 11.1977 14.25 11.75 14.25C12.3023 14.25 12.7501 13.8023 12.7501 13.25C12.7501 12.6977 12.3023 12.25 11.75 12.25Z\" fill=\"currentColor\"/>
                  <path d=\"M15.25 13.25C15.25 12.6977 15.6977 12.25 16.25 12.25C16.8023 12.25 17.2501 12.6977 17.2501 13.25C17.2501 13.8023 16.8024 14.25 16.2501 14.25C15.6978 14.25 15.25 13.8023 15.25 13.25Z\" fill=\"currentColor\"/>
                  <path d=\"M7.25 16.25C6.69772 16.25 6.25 16.6977 6.25 17.25C6.25 17.8023 6.69772 18.25 7.25 18.25C7.80228 18.25 8.2501 17.8023 8.2501 17.25C8.2501 16.6977 7.80228 16.25 7.25 16.25Z\" fill=\"currentColor\"/>
                  <path d=\"M10.75 17.25C10.75 16.6977 11.1977 16.25 11.75 16.25C12.3023 16.25 12.7501 16.6977 12.7501 17.25C12.7501 17.8023 12.3024 18.25 11.7501 18.25C11.1978 18.25 10.75 17.8023 10.75 17.25Z\" fill=\"currentColor\"/>
                  <path d=\"M16.25 16.25C15.6977 16.25 15.25 16.6977 15.25 17.25C15.25 17.8023 15.6977 18.25 16.25 18.25C16.8023 18.25 17.2501 17.8023 17.2501 17.25C17.2501 16.6977 16.8023 16.25 16.25 16.25Z\" fill=\"currentColor\"/>
                  <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M8.5 0.75C8.5 0.335786 8.16421 0 7.75 0C7.33579 0 7 0.335786 7 0.75V2.00007C6.19395 2.00064 5.53458 2.00569 4.99013 2.05018C4.36012 2.10165 3.81824 2.20963 3.32054 2.46322C2.52085 2.87068 1.87068 3.52085 1.46322 4.32054C1.20963 4.81824 1.10165 5.36012 1.05018 5.99013C0.999989 6.60439 0.999994 7.36493 1 8.31737V17.1826C0.999994 18.135 0.999989 18.8956 1.05018 19.5099C1.10165 20.1399 1.20963 20.6818 1.46322 21.1795C1.87068 21.9791 2.52085 22.6293 3.32054 23.0368C3.81824 23.2904 4.36012 23.3983 4.99013 23.4498C5.60438 23.5 6.3649 23.5 7.3173 23.5H16.1826C17.135 23.5 17.8956 23.5 18.5099 23.4498C19.1399 23.3983 19.6818 23.2904 20.1795 23.0368C20.9791 22.6293 21.6293 21.9791 22.0368 21.1795C22.2904 20.6818 22.3983 20.1399 22.4498 19.5099C22.5 18.8956 22.5 18.1351 22.5 17.1827V8.31737C22.5 7.36496 22.5 6.60438 22.4498 5.99013C22.3983 5.36012 22.2904 4.81824 22.0368 4.32054C21.6293 3.52085 20.9791 2.87068 20.1795 2.46322C19.6818 2.20963 19.1399 2.10165 18.5099 2.05018C17.9654 2.00569 17.3061 2.00064 16.5 2.00007V0.75C16.5 0.335786 16.1642 0 15.75 0C15.3358 0 15 0.335786 15 0.75V2H8.5V0.75ZM16.15 3.5C17.1425 3.5 17.8417 3.50058 18.3877 3.54519C18.925 3.58909 19.2475 3.67184 19.4985 3.79973C20.0159 4.06338 20.4366 4.48408 20.7003 5.00153C20.8282 5.25252 20.9109 5.57503 20.9548 6.11228C20.9855 6.4874 20.9953 6.93484 20.9985 7.5H2.5015C2.50468 6.93484 2.51455 6.4874 2.54519 6.11228C2.58909 5.57503 2.67184 5.25252 2.79973 5.00153C3.06338 4.48408 3.48408 4.06338 4.00153 3.79973C4.25252 3.67184 4.57503 3.58909 5.11228 3.54519C5.65829 3.50058 6.35753 3.5 7.35 3.5H16.15ZM2.5 9V17.15C2.5 18.1425 2.50058 18.8417 2.54519 19.3877C2.58909 19.925 2.67184 20.2475 2.79973 20.4985C3.06338 21.0159 3.48408 21.4366 4.00153 21.7003C4.25252 21.8282 4.57503 21.9109 5.11228 21.9548C5.65829 21.9994 6.35753 22 7.35 22H16.15C17.1425 22 17.8417 21.9994 18.3877 21.9548C18.925 21.9109 19.2475 21.8282 19.4985 21.7003C20.0159 21.4366 20.4366 21.0159 20.7003 20.4985C20.8282 20.2475 20.9109 19.925 20.9548 19.3877C20.9994 18.8417 21 18.1425 21 17.15V9H2.5Z\" fill=\"currentColor\"/>
                </svg>
                {{ operation.card_document_date }}
              {% endif %}
            </div>{# documents-item__date #}

            <div class=\"documents-item__counteragent\">
              {{ operation.first_counteragent }}
            </div>

          </div>{# documents-item__top-info #}

        </div>{# documents-item__top-left #}

        <div class=\"documents-item__number\">
          {% if operation.card_document_number %} № {{ operation.card_document_number }}{% endif %}
        </div>{# documents-item__doc-num #}

      </div>{# documents-item__top #}

      <div class=\"documents-item__middle\">

        <div class=\"documents-item__purpose\">
          Підстава:
            {% if operation.card_document_purpose %}
              <p>{{ operation.card_document_purpose }}</p>
          {% else %}
            <p>-</p>
          {% endif %}
        </div>

      </div>{# documents-item__middle #}

      <div class=\"documents-item__bottom\">

        {% if operation.items_count %}
        <div class=\"documents-item__count\">
            {% set count = operation.items_count %}
          {% set n = count % 100 %}
          {% set n1 = n % 10 %}

          {% if n > 10 and n < 20 %}
              {{ count }} товаров
          {% elseif n1 == 1 %}
              {{ count }} товар
          {% elseif n1 >= 2 and n1 <= 4 %}
              {{ count }} товара
          {% else %}
              {{ count }} товаров
          {% endif %}

        </div>
        {% endif %}
        {% if status == 'draft' %}
          <button
            type=\"button\"
            class=\"button button--nm button--secondary js-delete-draft\"
            data-operation-id=\"{{ operation.id }}\"
          >
            Видалити
          </button>
          <a href=\"/add-operation?operation_id={{ operation.id }}\" class=\"button button--nm button--brand\">Продовжити</a>
        {% else %}
          <a href=\"/documents/{{ operation.slug }}\" class=\"button button--nm button--brand\">Детальніше</a>
        {% endif %}

      </div>{# documents-item__bottom #}

    </div>{# documents-item #}

{% endfor %}
</div>{# documents-list #}", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\documents.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["partial" => 1, "set" => 4, "if" => 7, "for" => 18];
        static $filters = ["escape" => 8];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['partial', 'set', 'if', 'for'],
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
