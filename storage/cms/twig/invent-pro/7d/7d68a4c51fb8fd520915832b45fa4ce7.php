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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\pages\add-operation.htm */
class __TwigTemplate_40ce703c728fa1c747414b73b562f516 extends Template
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
        // line 2
        $context["isAdmin"] = false;
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "groups", [], "any", false, false, true, 3));
        foreach ($context['_seq'] as $context["_key"] => $context["group"]) {
            // line 4
            yield "    ";
            if ((CoreExtension::getAttribute($this->env, $this->source, $context["group"], "code", [], "any", false, false, true, 4) == "admin")) {
                // line 5
                yield "        ";
                $context["isAdmin"] = true;
                // line 6
                yield "    ";
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['group'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 8
        yield "
";
        // line 9
        if ((($context["user"] ?? null) && ($context["isAdmin"] ?? null))) {
            // line 10
            yield "
<div class=\"container--sm box--light\">

  <h2 class=\"operation-form__title\">";
            // line 13
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 13), "title", [], "any", false, false, true, 13), 13, $this->source), "html", null, true);
            yield "</h2>

  <form data-request=\"onAddOperation\" data-request-flash class=\"operation-form__content\" id =\"addOperationForm\" enctype=\"multipart/form-data\" data-request-files>

    <input type=\"hidden\" name=\"note_id\" value=\"";
            // line 17
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["note_id"] ?? null), 17, $this->source), "html", null, true);
            yield "\">
    <input type=\"hidden\" name=\"operation_id\" value=\"";
            // line 18
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["operation_id"] ?? null), 18, $this->source), "html", null, true);
            yield "\">

    <div class=\"operation-form__type-box\">
      ";
            // line 21
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(Twig\Extension\CoreExtension::slice($this->env->getCharset(), ($context["types"] ?? null), 0, 3));
            foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
                // line 22
                yield "        <label class=\"operation-form__type-box__label\">
          <input type=\"radio\" name=\"type_id\" value=\"";
                // line 23
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, true, 23), 23, $this->source), "html", null, true);
                yield "\" hidden>
          <span class=\"operation-form__type-box__name\">";
                // line 24
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "name", [], "any", false, false, true, 24), 24, $this->source), "html", null, true);
                yield "</span>
        </label>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['type'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 27
            yield "    </div>";
            // line 28
            yield "
    <div class=\"operation-form__box operation-form__counteragent\">

      <div class=\"operation-form__box__title\">Контрагент</div>
      <div class=\"operation-form__box__content form-floating\">
        <input type=\"text\" name=\"counteragent\" class=\"operation-form__input--counteragent form-input\" placeholder>
        <label for=\"counteragent\" class=\"form-label\">Часть/ДПРЗ</label>
      </div>

    </div>";
            // line 38
            yield "
    <div class=\"operation-form__box operation-form__documents\">

      <div class=\"operation-form__box__title\">Документы операции</div>

      <div id=\"operation-form__documents-wrapper\" class=\"operation-form__box__content\">

        <div class=\"operation-form__row--document\">

          <div class=\"operation-form__row--document-top\">

            <div class=\"form-floating operation-form__input--doc-name\">
              <div class=\"custom-select doc-name-select\" data-name=\"doc_name\">
                <div class=\"selected doc-name-selected\">Выберите документ</div>
                <div class=\"options dropdown\"></div>
              </div>
              <input type=\"hidden\" name=\"doc_name[]\" class=\"doc-name-input\">
            </div>

            <div class=\"form-floating operation-form__input--doc-num\">
              <input type=\"text\" name=\"doc_num[]\" class=\" form-input\" placeholder>
              <label for=\"doc_num\" class=\"form-label\">№</label>
            </div>

          </div>";
            // line 63
            yield "
          <div class=\"operation-form__row--document-middle\">

            <div class=\"form-floating operation-form__input--doc-purpose\">
              <input type=\"text\" name=\"doc_purpose[]\" class=\" form-input\" placeholder>
              <label for=\"doc_purpose\" class=\"form-label\">Мета</label>
            </div>

            <div class=\"form-floating operation-form__input--doc-date\">
              <input type=\"date\" name=\"doc_date[]\" class=\" form-input\" placeholder>
              <label for=\"doc_date\" class=\"form-label\">Дата</label>
            </div>

          </div>";
            // line 77
            yield "
          <div class=\"operation-form__button-box\">

            <button id=\"remove-document\" type=\"button\" class=\"button button--sm button--error button--ico-left remove-document-btn\">
            <svg width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
              <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
              <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
            </svg>
              Удалить документ
            </button>

             <button type=\"button\" class=\"button button--sm button--success button--ico-left doc-upload-btn\">
              <svg width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M6.70711 9.29289L11 13.5858V3C11 2.44772 11.4477 2 12 2C12.5523 2 13 2.44772 13 3V13.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.5196 16.8946 12.2652 17 12 17C11.7348 17 11.4804 16.8946 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289Z\" fill=\"currentColor\"/>
                <path d=\"M21 20C21.5523 20 22 20.4477 22 21C22 21.5523 21.5523 22 21 22H3C2.44772 22 2 21.5523 2 21C2 20.4477 2.44772 20 3 20H21Z\" fill=\"currentColor\"/>
              </svg>
              Загрузить PDF
            </button>

            <input type=\"file\" name=\"doc_file[]\" style=\"display: none;\" class=\"doc-file-input\">

          </div>";
            // line 99
            yield "
        </div>";
            // line 101
            yield "
      </div>";
            // line 103
            yield "
      <div class=\"operation-form__button-box\">
        <div class=\"button--outside\">
          <button id=\"add-document\" type=\"button\" class=\"button button--sm button--secondary button--ico-left\">
            <svg width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
              <path d=\"M11 19.75C11 20.1642 11.3358 20.5 11.75 20.5C12.1642 20.5 12.5 20.1642 12.5 19.75V12.5H19.75C20.1642 12.5 20.5 12.1642 20.5 11.75C20.5 11.3358 20.1642 11 19.75 11H12.5V3.75C12.5 3.33579 12.1642 3 11.75 3C11.3358 3 11 3.33579 11 3.75V11H3.75C3.33579 11 3 11.3358 3 11.75C3 12.1642 3.33579 12.5 3.75 12.5H11V19.75Z\" fill=\"currentColor\"/>
            </svg>
            Добавить документ
          </button>
        </div>
      </div>

    </div>";
            // line 116
            yield "
    <div class=\"operation-form__box operation-form__products\">

      <div class=\"operation-form__box__title\">Товары в операции</div>

      <div id=\"operation-form__products-wrapper\"  class=\"operation-form__box__content\">

        <div class=\"operation-form__row--product operation-form__box__content--products\">

          <div class=\"form-floating operation-form__input--name\">
            <input type=\"text\" name=\"name[]\" class=\"form-input\" autocomplete=\"off\" placeholder>
            <label for=\"name\" class=\"form-label\">Наименование</label>
          </div>
          <div class=\"form-floating operation-form__input--inv_number\">
            <input type=\"text\" name=\"inv_number[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"inv_number\" class=\"form-label\">Номенклатурный №</label>
            <div class=\"operation-form__input--inv-suggestion\"></div>
          </div>
          <div class=\"form-floating operation-form__input--unit\">
            <input type=\"text\" name=\"unit[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"unit\" class=\"form-label\">Ед.изм</label>
          </div>
          <div class=\"form-floating operation-form__input--price\">
            <input type=\"text\" name=\"price[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"price\" class=\"form-label\">Цена</label>
          </div>
          <div class=\"form-floating operation-form__input--quantity\">
            <input type=\"text\" name=\"quantity[]\" class=\" form-input\" autocomplete=\"off\" placeholder >
            <label for=\"quantity\" class=\"form-label\">К-во</label>
          </div>
          <div class=\"form-floating operation-form__input--sum\">
            <input type=\"text\" name=\"sum[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"sum\" class=\"form-label\">Сумма</label>
          </div>

          <div class=\"operation-form__button-box\">

            <button id=\"remove-product\" type=\"button\" class=\"button button--sm button--error button--ico-left remove-product-btn\">
            <svg width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
              <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
              <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
            </svg>
              Удалить товар
            </button>

            <input type=\"file\" name=\"doc_file[]\" style=\"display: none;\" id=\"docFileInput\">

          </div>";
            // line 164
            yield "
        </div>";
            // line 166
            yield "
      </div>";
            // line 168
            yield "
      <div class=\"operation-form__button-box\">
        <div class=\"button--outside\">
          <button id=\"add-product\" type=\"button\" class=\"button button--sm button--secondary button--ico-left\">Добавить продукт</button>
        </div>
      </div>

    </div>";
            // line 176
            yield "
    <div class=\"operation-form__button-box\">
      <button type=\"submit\" class=\"button button--nm button--brand\">Сохранить операцию</button>
    </div>

  </form>

</div>";
            // line 184
            yield "
<script>
  window.docNameOptions = {
    incoming_transfer: [
      \"Накладна вимога\",
      \"Акт внутрішнього переміщення\",
      \"Акт технічного стану ТЗ\",
      \"Акт прийому-передачі\"
    ],
    writeoff: [
      \"Дефектний акт\",
      \"Акт встановлення\",
      \"Акт виконаних робіт\",
      \"Акт списання запасів\"
    ]
  };
</script>

";
            // line 202
            if ((($tmp = ($context["prefill_products"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 203
                yield "<script>
  window.prefill_products = ";
                // line 204
                yield json_encode($this->sandbox->ensureToStringAllowed(($context["prefill_products"] ?? null), 204, $this->source));
                yield ";
  window.prefill_note_id = ";
                // line 205
                yield json_encode($this->sandbox->ensureToStringAllowed(($context["note_id"] ?? null), 205, $this->source));
                yield ";
</script>
";
            }
            // line 208
            yield "
";
            // line 209
            if ((($tmp = ($context["prefill_operation"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 210
                yield "<script>
  window.prefill_operation = ";
                // line 211
                yield json_encode($this->sandbox->ensureToStringAllowed(($context["prefill_operation"] ?? null), 211, $this->source));
                yield ";
  window.prefill_documents = ";
                // line 212
                yield json_encode(((array_key_exists("prefill_documents", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["prefill_documents"] ?? null), 212, $this->source), [])) : ([])));
                yield ";
</script>
";
            }
            // line 215
            yield "
";
        } else {
            // line 217
            yield "    <p>У вас нет прав для добавления новой операции.</p>
";
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\add-operation.htm";
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
        return array (  337 => 217,  333 => 215,  327 => 212,  323 => 211,  320 => 210,  318 => 209,  315 => 208,  309 => 205,  305 => 204,  302 => 203,  300 => 202,  280 => 184,  271 => 176,  262 => 168,  259 => 166,  256 => 164,  207 => 116,  193 => 103,  190 => 101,  187 => 99,  164 => 77,  149 => 63,  123 => 38,  112 => 28,  110 => 27,  101 => 24,  97 => 23,  94 => 22,  90 => 21,  84 => 18,  80 => 17,  73 => 13,  68 => 10,  66 => 9,  63 => 8,  56 => 6,  53 => 5,  50 => 4,  46 => 3,  44 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{# Ниже проверка на админа #}
{% set isAdmin = false %}
{% for group in user.groups %}
    {% if group.code == 'admin' %}
        {% set isAdmin = true %}
    {% endif %}
{% endfor %}

{% if user and isAdmin %}

<div class=\"container--sm box--light\">

  <h2 class=\"operation-form__title\">{{ this.page.title }}</h2>

  <form data-request=\"onAddOperation\" data-request-flash class=\"operation-form__content\" id =\"addOperationForm\" enctype=\"multipart/form-data\" data-request-files>

    <input type=\"hidden\" name=\"note_id\" value=\"{{ note_id }}\">
    <input type=\"hidden\" name=\"operation_id\" value=\"{{ operation_id }}\">

    <div class=\"operation-form__type-box\">
      {% for type in types|slice(0, 3) %}
        <label class=\"operation-form__type-box__label\">
          <input type=\"radio\" name=\"type_id\" value=\"{{ type.id }}\" hidden>
          <span class=\"operation-form__type-box__name\">{{ type.name }}</span>
        </label>
      {% endfor %}
    </div>{# operation-form__type-box #}

    <div class=\"operation-form__box operation-form__counteragent\">

      <div class=\"operation-form__box__title\">Контрагент</div>
      <div class=\"operation-form__box__content form-floating\">
        <input type=\"text\" name=\"counteragent\" class=\"operation-form__input--counteragent form-input\" placeholder>
        <label for=\"counteragent\" class=\"form-label\">Часть/ДПРЗ</label>
      </div>

    </div>{# operation-form__box operation-form__agent #}

    <div class=\"operation-form__box operation-form__documents\">

      <div class=\"operation-form__box__title\">Документы операции</div>

      <div id=\"operation-form__documents-wrapper\" class=\"operation-form__box__content\">

        <div class=\"operation-form__row--document\">

          <div class=\"operation-form__row--document-top\">

            <div class=\"form-floating operation-form__input--doc-name\">
              <div class=\"custom-select doc-name-select\" data-name=\"doc_name\">
                <div class=\"selected doc-name-selected\">Выберите документ</div>
                <div class=\"options dropdown\"></div>
              </div>
              <input type=\"hidden\" name=\"doc_name[]\" class=\"doc-name-input\">
            </div>

            <div class=\"form-floating operation-form__input--doc-num\">
              <input type=\"text\" name=\"doc_num[]\" class=\" form-input\" placeholder>
              <label for=\"doc_num\" class=\"form-label\">№</label>
            </div>

          </div>{# operation-form__row--top #}

          <div class=\"operation-form__row--document-middle\">

            <div class=\"form-floating operation-form__input--doc-purpose\">
              <input type=\"text\" name=\"doc_purpose[]\" class=\" form-input\" placeholder>
              <label for=\"doc_purpose\" class=\"form-label\">Мета</label>
            </div>

            <div class=\"form-floating operation-form__input--doc-date\">
              <input type=\"date\" name=\"doc_date[]\" class=\" form-input\" placeholder>
              <label for=\"doc_date\" class=\"form-label\">Дата</label>
            </div>

          </div>{# operation-form__row--document-middle #}

          <div class=\"operation-form__button-box\">

            <button id=\"remove-document\" type=\"button\" class=\"button button--sm button--error button--ico-left remove-document-btn\">
            <svg width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
              <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
              <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
            </svg>
              Удалить документ
            </button>

             <button type=\"button\" class=\"button button--sm button--success button--ico-left doc-upload-btn\">
              <svg width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                <path d=\"M6.70711 9.29289L11 13.5858V3C11 2.44772 11.4477 2 12 2C12.5523 2 13 2.44772 13 3V13.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.5196 16.8946 12.2652 17 12 17C11.7348 17 11.4804 16.8946 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289Z\" fill=\"currentColor\"/>
                <path d=\"M21 20C21.5523 20 22 20.4477 22 21C22 21.5523 21.5523 22 21 22H3C2.44772 22 2 21.5523 2 21C2 20.4477 2.44772 20 3 20H21Z\" fill=\"currentColor\"/>
              </svg>
              Загрузить PDF
            </button>

            <input type=\"file\" name=\"doc_file[]\" style=\"display: none;\" class=\"doc-file-input\">

          </div>{# operation-form__button-box--document#}

        </div>{# operation-form__row--document operation-form__box__content--documents #}

      </div>{# operation-form__box__content#}

      <div class=\"operation-form__button-box\">
        <div class=\"button--outside\">
          <button id=\"add-document\" type=\"button\" class=\"button button--sm button--secondary button--ico-left\">
            <svg width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
              <path d=\"M11 19.75C11 20.1642 11.3358 20.5 11.75 20.5C12.1642 20.5 12.5 20.1642 12.5 19.75V12.5H19.75C20.1642 12.5 20.5 12.1642 20.5 11.75C20.5 11.3358 20.1642 11 19.75 11H12.5V3.75C12.5 3.33579 12.1642 3 11.75 3C11.3358 3 11 3.33579 11 3.75V11H3.75C3.33579 11 3 11.3358 3 11.75C3 12.1642 3.33579 12.5 3.75 12.5H11V19.75Z\" fill=\"currentColor\"/>
            </svg>
            Добавить документ
          </button>
        </div>
      </div>

    </div>{# operation-form__box operation-form__documents #}

    <div class=\"operation-form__box operation-form__products\">

      <div class=\"operation-form__box__title\">Товары в операции</div>

      <div id=\"operation-form__products-wrapper\"  class=\"operation-form__box__content\">

        <div class=\"operation-form__row--product operation-form__box__content--products\">

          <div class=\"form-floating operation-form__input--name\">
            <input type=\"text\" name=\"name[]\" class=\"form-input\" autocomplete=\"off\" placeholder>
            <label for=\"name\" class=\"form-label\">Наименование</label>
          </div>
          <div class=\"form-floating operation-form__input--inv_number\">
            <input type=\"text\" name=\"inv_number[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"inv_number\" class=\"form-label\">Номенклатурный №</label>
            <div class=\"operation-form__input--inv-suggestion\"></div>
          </div>
          <div class=\"form-floating operation-form__input--unit\">
            <input type=\"text\" name=\"unit[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"unit\" class=\"form-label\">Ед.изм</label>
          </div>
          <div class=\"form-floating operation-form__input--price\">
            <input type=\"text\" name=\"price[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"price\" class=\"form-label\">Цена</label>
          </div>
          <div class=\"form-floating operation-form__input--quantity\">
            <input type=\"text\" name=\"quantity[]\" class=\" form-input\" autocomplete=\"off\" placeholder >
            <label for=\"quantity\" class=\"form-label\">К-во</label>
          </div>
          <div class=\"form-floating operation-form__input--sum\">
            <input type=\"text\" name=\"sum[]\" class=\" form-input\" autocomplete=\"off\" placeholder>
            <label for=\"sum\" class=\"form-label\">Сумма</label>
          </div>

          <div class=\"operation-form__button-box\">

            <button id=\"remove-product\" type=\"button\" class=\"button button--sm button--error button--ico-left remove-product-btn\">
            <svg width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
              <path d=\"M21.07 5.23C19.46 5.07 17.85 4.95 16.23 4.86V4.85L16.01 3.55C15.86 2.63 15.64 1.25 13.3 1.25H10.68C8.34997 1.25 8.12997 2.57 7.96997 3.54L7.75997 4.82C6.82997 4.88 5.89997 4.94 4.96997 5.03L2.92997 5.23C2.50997 5.27 2.20997 5.64 2.24997 6.05C2.28997 6.46 2.64997 6.76 3.06997 6.72L5.10997 6.52C10.35 6 15.63 6.2 20.93 6.73C20.96 6.73 20.98 6.73 21.01 6.73C21.39 6.73 21.72 6.44 21.76 6.05C21.79 5.64 21.49 5.27 21.07 5.23Z\" fill=\"currentColor\"/>
              <path d=\"M19.23 8.14C18.99 7.89 18.66 7.75 18.32 7.75H5.67997C5.33997 7.75 4.99997 7.89 4.76997 8.14C4.53997 8.39 4.40997 8.73 4.42997 9.08L5.04997 19.34C5.15997 20.86 5.29997 22.76 8.78997 22.76H15.21C18.7 22.76 18.84 20.87 18.95 19.34L19.57 9.09C19.59 8.73 19.46 8.39 19.23 8.14ZM13.66 17.75H10.33C9.91997 17.75 9.57997 17.41 9.57997 17C9.57997 16.59 9.91997 16.25 10.33 16.25H13.66C14.07 16.25 14.41 16.59 14.41 17C14.41 17.41 14.07 17.75 13.66 17.75ZM14.5 13.75H9.49997C9.08997 13.75 8.74997 13.41 8.74997 13C8.74997 12.59 9.08997 12.25 9.49997 12.25H14.5C14.91 12.25 15.25 12.59 15.25 13C15.25 13.41 14.91 13.75 14.5 13.75Z\" fill=\"currentColor\"/>
            </svg>
              Удалить товар
            </button>

            <input type=\"file\" name=\"doc_file[]\" style=\"display: none;\" id=\"docFileInput\">

          </div>{# operation-form__button-box--product#}

        </div>{# operation-form__row--product operation-form__box__content--products #}

      </div>{# operation-form__box__content#}

      <div class=\"operation-form__button-box\">
        <div class=\"button--outside\">
          <button id=\"add-product\" type=\"button\" class=\"button button--sm button--secondary button--ico-left\">Добавить продукт</button>
        </div>
      </div>

    </div>{# operation-form__box operation-form__products #}

    <div class=\"operation-form__button-box\">
      <button type=\"submit\" class=\"button button--nm button--brand\">Сохранить операцию</button>
    </div>

  </form>

</div>{# operation-form__container box--light #}

<script>
  window.docNameOptions = {
    incoming_transfer: [
      \"Накладна вимога\",
      \"Акт внутрішнього переміщення\",
      \"Акт технічного стану ТЗ\",
      \"Акт прийому-передачі\"
    ],
    writeoff: [
      \"Дефектний акт\",
      \"Акт встановлення\",
      \"Акт виконаних робіт\",
      \"Акт списання запасів\"
    ]
  };
</script>

{% if prefill_products %}
<script>
  window.prefill_products = {{ prefill_products | json_encode | raw }};
  window.prefill_note_id = {{ note_id | json_encode | raw }};
</script>
{% endif %}

{% if prefill_operation %}
<script>
  window.prefill_operation = {{ prefill_operation | json_encode | raw }};
  window.prefill_documents = {{ prefill_documents | default([]) | json_encode | raw }};
</script>
{% endif %}

{% else %}
    <p>У вас нет прав для добавления новой операции.</p>
{% endif %}", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\add-operation.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 2, "for" => 3, "if" => 4];
        static $filters = ["escape" => 13, "slice" => 21, "raw" => 204, "json_encode" => 204, "default" => 212];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'for', 'if'],
                ['escape', 'slice', 'raw', 'json_encode', 'default'],
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
