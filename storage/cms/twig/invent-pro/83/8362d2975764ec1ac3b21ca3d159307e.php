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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\global\bottom-bar.htm */
class __TwigTemplate_9dd26fce3a410a46fabba7da322b6fc3 extends Template
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
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 1), "url", [], "any", false, false, true, 1) != "/add-operation")) {
            // line 2
            yield "
<div class=\"category-menu__overlay hidden\"></div>

<div id=\"bottomBar\" class=\"bottom-bar hidden\">

  <div class=\"bottom-bar__left\">

    <button class=\"bottom-bar__close\">
      <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
        <path d=\"M15.7123 7.22703C16.0052 6.93414 16.4801 6.93414 16.773 7.22703C17.0658 7.51992 17.0658 7.9948 16.773 8.28769L8.28767 16.773C7.99478 17.0659 7.5199 17.0659 7.22701 16.773C6.93412 16.4801 6.93412 16.0052 7.22701 15.7123L15.7123 7.22703Z\" fill=\"currentColor\"/>
        <path d=\"M15.7123 16.773L7.22701 8.28769C6.93412 7.9948 6.93412 7.51992 7.22701 7.22703C7.5199 6.93414 7.99478 6.93414 8.28767 7.22703L16.773 15.7123C17.0658 16.0052 17.0658 16.4801 16.773 16.773C16.4801 17.0659 16.0052 17.0659 15.7123 16.773Z\" fill=\"currentColor\"/>
      </svg>
    </button>

    <div class=\"bottom-bar__count-box\">
      <div id=\"bottomBarCount\" class=\"bottom-bar__count\">4</div>
      <div class=\"bottom-bar__count-text\">товара выбрано</div>
    </div>

  </div>";
            // line 22
            yield "
  <div class=\"bottom-bar__button-box\">

    ";
            // line 25
            if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 25), "url", [], "any", false, false, true, 25) == "/operation-history")) {
                // line 26
                yield "      <button type=\"submit\" id=\"editOperation\" class=\"button--round button--dark button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path d=\"M16.7216 1.13459C17.2739 0.955135 17.8688 0.955135 18.4212 1.13459C18.7811 1.25156 19.0808 1.4563 19.3714 1.70298C19.6499 1.93948 19.9652 2.25473 20.3429 2.63242L20.8604 3.15C21.2382 3.5277 21.5534 3.84297 21.79 4.12157C22.0366 4.41213 22.2414 4.71178 22.3583 5.07178C22.5378 5.6241 22.5378 6.21905 22.3583 6.77137C22.2414 7.13136 22.0366 7.43101 21.79 7.72158C21.5534 8.00017 21.2382 8.31541 20.8605 8.6931L19.8676 9.68603C19.6696 9.88404 19.5705 9.98304 19.4564 10.0201C19.356 10.0528 19.2478 10.0528 19.1474 10.0201C19.0332 9.98304 18.9342 9.88404 18.7362 9.68603L13.8069 4.75674C13.6089 4.55873 13.5099 4.45973 13.4728 4.34556C13.4402 4.24514 13.4402 4.13697 13.4728 4.03654C13.5099 3.92238 13.6089 3.82338 13.8069 3.62537L14.8005 2.63179C15.1779 2.25435 15.4931 1.93918 15.7715 1.70279C16.062 1.4562 16.3616 1.25155 16.7216 1.13459Z\" fill=\"currentColor\"/>
          <path d=\"M12.7462 5.8174C12.5482 5.61939 12.4492 5.52038 12.3351 5.48329C12.2346 5.45066 12.1265 5.45066 12.026 5.48329C11.9119 5.52038 11.8129 5.61939 11.6149 5.8174L2.80848 14.6237C2.6511 14.7809 2.51197 14.9198 2.39733 15.0841C2.29646 15.2286 2.21292 15.3845 2.14843 15.5485C2.07511 15.7349 2.03611 15.9294 1.99191 16.1498L1.09829 20.5896C1.06307 20.7644 1.02735 20.9417 1.01102 21.0924C0.99374 21.2519 0.983801 21.4866 1.08453 21.7304C1.21142 22.0375 1.45538 22.2815 1.76251 22.4084C2.00632 22.5091 2.24097 22.4992 2.40048 22.4819C2.55119 22.4656 2.72849 22.4298 2.90331 22.3946L7.34314 21.501C7.56352 21.4568 7.75799 21.4178 7.94443 21.3445C8.10843 21.28 8.2643 21.1965 8.40881 21.0956C8.57309 20.9809 8.71324 20.8406 8.87206 20.6815L17.6755 11.8781C17.8735 11.68 17.9725 11.581 18.0096 11.4669C18.0423 11.3665 18.0423 11.2583 18.0096 11.1579C17.9725 11.0437 17.8735 10.9447 17.6755 10.7467L12.7462 5.8174Z\" fill=\"currentColor\"/>
        </svg>
        Редактировать
      </button>
    ";
            }
            // line 34
            yield "
    ";
            // line 35
            if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 35), "url", [], "any", false, false, true, 35) == "/warehouse")) {
                // line 36
                yield "      <div class=\"bottom-bar__add-category-section\">
        <button type=\"submit\" id=\"addToCategory\" class=\"button--round button--dark button--inset-shadow\">
          <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
            <path d=\"M13.5532 3.13155C12.8259 1.62281 10.6772 1.62282 9.94997 3.13155L7.8192 7.55186C7.78256 7.62788 7.71003 7.68028 7.62635 7.6912L2.74353 8.32835C1.06954 8.54678 0.40249 10.613 1.63273 11.769L5.18951 15.1113C5.25163 15.1697 5.27973 15.2557 5.26405 15.3395L4.3686 20.1265C4.05971 21.7778 5.80115 23.0511 7.28107 22.256L11.6333 19.9178C11.7071 19.8781 11.796 19.8781 11.8699 19.9178L16.2221 22.256C17.702 23.0511 19.4435 21.7778 19.1346 20.1265L18.2391 15.3395C18.2234 15.2557 18.2515 15.1697 18.3137 15.1113L21.8704 11.769C23.1007 10.613 22.4336 8.54678 20.7596 8.32835L15.8768 7.6912C15.7931 7.68028 15.7206 7.62788 15.684 7.55186L13.5532 3.13155Z\" fill=\"currentColor\"/>
          </svg>
          Дать категорию
        </button>
        <!-- Меню выбора категории -->
        <div id=\"categoryMenu\" class=\"category-menu hidden\">
          <ul class=\"category-menu__list\">
              ";
                // line 46
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(($context["categories"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["cat"]) {
                    // line 47
                    yield "                  ";
                    if ((CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "nest_depth", [], "any", false, false, true, 47) == 0)) {
                        // line 48
                        yield "                      <li class=\"category-menu__item\" data-id=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "id", [], "any", false, false, true, 48), 48, $this->source), "html", null, true);
                        yield "\">
                          <div class=\"category-menu__item-left\">";
                        // line 49
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "name", [], "any", false, false, true, 49), 49, $this->source), "html", null, true);
                        yield "<span>";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "desc", [], "any", false, false, true, 49), 49, $this->source), "html", null, true);
                        yield "</span></div>

                          ";
                        // line 51
                        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "children", [], "any", false, false, true, 51))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                            // line 52
                            yield "                              <div class=\"category-menu__item-arrow\">
                                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                      <path d=\"M6.29289 9.29289C6.68342 8.90237 7.31658 8.90237 7.70711 9.29289L12 13.5858L16.2929 9.29289C16.6834 8.90237 17.3166 8.90237 17.7071 9.29289C18.0976 9.68342 18.0976 10.3166 17.7071 10.7071L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L6.29289 10.7071C5.90237 10.3166 5.90237 9.68342 6.29289 9.29289Z\" fill=\"currentColor\"/>
                                  </svg>
                              </div>

                              <ul class=\"category-menu__children hidden\">
                                  ";
                            // line 59
                            $context['_parent'] = $context;
                            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["cat"], "children", [], "any", false, false, true, 59));
                            foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                                // line 60
                                yield "                                      <li class=\"category-menu__item\" data-id=\"";
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["child"], "id", [], "any", false, false, true, 60), 60, $this->source), "html", null, true);
                                yield "\">
                                          <div class=\"category-menu__item-left\">";
                                // line 61
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["child"], "name", [], "any", false, false, true, 61), 61, $this->source), "html", null, true);
                                yield "<span>";
                                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["child"], "desc", [], "any", false, false, true, 61), 61, $this->source), "html", null, true);
                                yield "</span></div>

                                          ";
                                // line 63
                                if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["child"], "children", [], "any", false, false, true, 63))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                                    // line 64
                                    yield "                                              <div class=\"category-menu__item-arrow\">
                                                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                                      <path d=\"M6.29289 9.29289C6.68342 8.90237 7.31658 8.90237 7.70711 9.29289L12 13.5858L16.2929 9.29289C16.6834 8.90237 17.3166 8.90237 17.7071 9.29289C18.0976 9.68342 18.0976 10.3166 17.7071 10.7071L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L6.29289 10.7071C5.90237 10.3166 5.90237 9.68342 6.29289 9.29289Z\" fill=\"currentColor\"/>
                                                  </svg>
                                              </div>

                                              <ul class=\"category-menu__children hidden\">
                                                  ";
                                    // line 71
                                    $context['_parent'] = $context;
                                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["child"], "children", [], "any", false, false, true, 71));
                                    foreach ($context['_seq'] as $context["_key"] => $context["subchild"]) {
                                        // line 72
                                        yield "                                                      <li class=\"category-menu__item\" data-id=\"";
                                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["subchild"], "id", [], "any", false, false, true, 72), 72, $this->source), "html", null, true);
                                        yield "\">
                                                          <div class=\"category-menu__item-left\">";
                                        // line 73
                                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["subchild"], "name", [], "any", false, false, true, 73), 73, $this->source), "html", null, true);
                                        yield "<span>";
                                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["subchild"], "desc", [], "any", false, false, true, 73), 73, $this->source), "html", null, true);
                                        yield "</span></div>
                                                      </li>
                                                  ";
                                    }
                                    $_parent = $context['_parent'];
                                    unset($context['_seq'], $context['_key'], $context['subchild'], $context['_parent']);
                                    $context = array_intersect_key($context, $_parent) + $_parent;
                                    // line 76
                                    yield "                                              </ul>
                                          ";
                                }
                                // line 78
                                yield "                                      </li>
                                  ";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_key'], $context['child'], $context['_parent']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 80
                            yield "                              </ul>
                          ";
                        }
                        // line 82
                        yield "                      </li>
                  ";
                    }
                    // line 84
                    yield "              ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['cat'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 85
                yield "          </ul>
      </div>



      </div>";
                // line 91
                yield "
      <!-- Notes buttons -->
      <button type=\"button\" id=\"createNote\" class=\"button--round button--brand button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z\" fill=\"currentColor\"/></svg>
        Заметки — Создать
      </button>

      <button type=\"button\" id=\"addToNote\" class=\"button--round button--dark button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M21 6h-2v9H5v2h14c1.1 0 2-.9 2-2V6zM3 6v12c0 1.1.9 2 2 2h11v-2H5V6H3zm9-4l-2 2h4l-2-2z\" fill=\"currentColor\"/></svg>
        Добавить в заметку
      </button>

      <button type=\"submit\" id=\"createOperation\" class=\"button--round button--brand button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M0 11.75C0 5.26065 5.26065 0 11.75 0C18.2393 0 23.5 5.26065 23.5 11.75C23.5 18.2393 18.2393 23.5 11.75 23.5C5.26065 23.5 0 18.2393 0 11.75ZM11.75 16.5C11.3358 16.5 11 16.1642 11 15.75V12.5H7.75C7.33579 12.5 7 12.1642 7 11.75C7 11.3358 7.33579 11 7.75 11H11V7.75C11 7.33579 11.3358 7 11.75 7C12.1642 7 12.5 7.33579 12.5 7.75V11H15.75C16.1642 11 16.5 11.3358 16.5 11.75C16.5 12.1642 16.1642 12.5 15.75 12.5H12.5V15.75C12.5 16.1642 12.1642 16.5 11.75 16.5Z\" fill=\"currentColor\"/>
        </svg>
        Создать
      </button>
    ";
            }
            // line 110
            yield "
  </div>";
            // line 112
            yield "
</div>";
            // line 114
            yield "
";
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\global\\bottom-bar.htm";
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
        return array (  237 => 114,  234 => 112,  231 => 110,  210 => 91,  203 => 85,  197 => 84,  193 => 82,  189 => 80,  182 => 78,  178 => 76,  167 => 73,  162 => 72,  158 => 71,  149 => 64,  147 => 63,  140 => 61,  135 => 60,  131 => 59,  122 => 52,  120 => 51,  113 => 49,  108 => 48,  105 => 47,  101 => 46,  89 => 36,  87 => 35,  84 => 34,  74 => 26,  72 => 25,  67 => 22,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if this.page.url != '/add-operation' %}

<div class=\"category-menu__overlay hidden\"></div>

<div id=\"bottomBar\" class=\"bottom-bar hidden\">

  <div class=\"bottom-bar__left\">

    <button class=\"bottom-bar__close\">
      <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
        <path d=\"M15.7123 7.22703C16.0052 6.93414 16.4801 6.93414 16.773 7.22703C17.0658 7.51992 17.0658 7.9948 16.773 8.28769L8.28767 16.773C7.99478 17.0659 7.5199 17.0659 7.22701 16.773C6.93412 16.4801 6.93412 16.0052 7.22701 15.7123L15.7123 7.22703Z\" fill=\"currentColor\"/>
        <path d=\"M15.7123 16.773L7.22701 8.28769C6.93412 7.9948 6.93412 7.51992 7.22701 7.22703C7.5199 6.93414 7.99478 6.93414 8.28767 7.22703L16.773 15.7123C17.0658 16.0052 17.0658 16.4801 16.773 16.773C16.4801 17.0659 16.0052 17.0659 15.7123 16.773Z\" fill=\"currentColor\"/>
      </svg>
    </button>

    <div class=\"bottom-bar__count-box\">
      <div id=\"bottomBarCount\" class=\"bottom-bar__count\">4</div>
      <div class=\"bottom-bar__count-text\">товара выбрано</div>
    </div>

  </div>{# bottom-bar__left #}

  <div class=\"bottom-bar__button-box\">

    {% if this.page.url == '/operation-history' %}
      <button type=\"submit\" id=\"editOperation\" class=\"button--round button--dark button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path d=\"M16.7216 1.13459C17.2739 0.955135 17.8688 0.955135 18.4212 1.13459C18.7811 1.25156 19.0808 1.4563 19.3714 1.70298C19.6499 1.93948 19.9652 2.25473 20.3429 2.63242L20.8604 3.15C21.2382 3.5277 21.5534 3.84297 21.79 4.12157C22.0366 4.41213 22.2414 4.71178 22.3583 5.07178C22.5378 5.6241 22.5378 6.21905 22.3583 6.77137C22.2414 7.13136 22.0366 7.43101 21.79 7.72158C21.5534 8.00017 21.2382 8.31541 20.8605 8.6931L19.8676 9.68603C19.6696 9.88404 19.5705 9.98304 19.4564 10.0201C19.356 10.0528 19.2478 10.0528 19.1474 10.0201C19.0332 9.98304 18.9342 9.88404 18.7362 9.68603L13.8069 4.75674C13.6089 4.55873 13.5099 4.45973 13.4728 4.34556C13.4402 4.24514 13.4402 4.13697 13.4728 4.03654C13.5099 3.92238 13.6089 3.82338 13.8069 3.62537L14.8005 2.63179C15.1779 2.25435 15.4931 1.93918 15.7715 1.70279C16.062 1.4562 16.3616 1.25155 16.7216 1.13459Z\" fill=\"currentColor\"/>
          <path d=\"M12.7462 5.8174C12.5482 5.61939 12.4492 5.52038 12.3351 5.48329C12.2346 5.45066 12.1265 5.45066 12.026 5.48329C11.9119 5.52038 11.8129 5.61939 11.6149 5.8174L2.80848 14.6237C2.6511 14.7809 2.51197 14.9198 2.39733 15.0841C2.29646 15.2286 2.21292 15.3845 2.14843 15.5485C2.07511 15.7349 2.03611 15.9294 1.99191 16.1498L1.09829 20.5896C1.06307 20.7644 1.02735 20.9417 1.01102 21.0924C0.99374 21.2519 0.983801 21.4866 1.08453 21.7304C1.21142 22.0375 1.45538 22.2815 1.76251 22.4084C2.00632 22.5091 2.24097 22.4992 2.40048 22.4819C2.55119 22.4656 2.72849 22.4298 2.90331 22.3946L7.34314 21.501C7.56352 21.4568 7.75799 21.4178 7.94443 21.3445C8.10843 21.28 8.2643 21.1965 8.40881 21.0956C8.57309 20.9809 8.71324 20.8406 8.87206 20.6815L17.6755 11.8781C17.8735 11.68 17.9725 11.581 18.0096 11.4669C18.0423 11.3665 18.0423 11.2583 18.0096 11.1579C17.9725 11.0437 17.8735 10.9447 17.6755 10.7467L12.7462 5.8174Z\" fill=\"currentColor\"/>
        </svg>
        Редактировать
      </button>
    {% endif %}

    {% if this.page.url == '/warehouse' %}
      <div class=\"bottom-bar__add-category-section\">
        <button type=\"submit\" id=\"addToCategory\" class=\"button--round button--dark button--inset-shadow\">
          <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
            <path d=\"M13.5532 3.13155C12.8259 1.62281 10.6772 1.62282 9.94997 3.13155L7.8192 7.55186C7.78256 7.62788 7.71003 7.68028 7.62635 7.6912L2.74353 8.32835C1.06954 8.54678 0.40249 10.613 1.63273 11.769L5.18951 15.1113C5.25163 15.1697 5.27973 15.2557 5.26405 15.3395L4.3686 20.1265C4.05971 21.7778 5.80115 23.0511 7.28107 22.256L11.6333 19.9178C11.7071 19.8781 11.796 19.8781 11.8699 19.9178L16.2221 22.256C17.702 23.0511 19.4435 21.7778 19.1346 20.1265L18.2391 15.3395C18.2234 15.2557 18.2515 15.1697 18.3137 15.1113L21.8704 11.769C23.1007 10.613 22.4336 8.54678 20.7596 8.32835L15.8768 7.6912C15.7931 7.68028 15.7206 7.62788 15.684 7.55186L13.5532 3.13155Z\" fill=\"currentColor\"/>
          </svg>
          Дать категорию
        </button>
        <!-- Меню выбора категории -->
        <div id=\"categoryMenu\" class=\"category-menu hidden\">
          <ul class=\"category-menu__list\">
              {% for cat in categories %}
                  {% if cat.nest_depth == 0 %}
                      <li class=\"category-menu__item\" data-id=\"{{ cat.id }}\">
                          <div class=\"category-menu__item-left\">{{ cat.name }}<span>{{ cat.desc }}</span></div>

                          {% if cat.children|length %}
                              <div class=\"category-menu__item-arrow\">
                                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                      <path d=\"M6.29289 9.29289C6.68342 8.90237 7.31658 8.90237 7.70711 9.29289L12 13.5858L16.2929 9.29289C16.6834 8.90237 17.3166 8.90237 17.7071 9.29289C18.0976 9.68342 18.0976 10.3166 17.7071 10.7071L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L6.29289 10.7071C5.90237 10.3166 5.90237 9.68342 6.29289 9.29289Z\" fill=\"currentColor\"/>
                                  </svg>
                              </div>

                              <ul class=\"category-menu__children hidden\">
                                  {% for child in cat.children %}
                                      <li class=\"category-menu__item\" data-id=\"{{ child.id }}\">
                                          <div class=\"category-menu__item-left\">{{ child.name }}<span>{{ child.desc }}</span></div>

                                          {% if child.children|length %}
                                              <div class=\"category-menu__item-arrow\">
                                                  <svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                                      <path d=\"M6.29289 9.29289C6.68342 8.90237 7.31658 8.90237 7.70711 9.29289L12 13.5858L16.2929 9.29289C16.6834 8.90237 17.3166 8.90237 17.7071 9.29289C18.0976 9.68342 18.0976 10.3166 17.7071 10.7071L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L6.29289 10.7071C5.90237 10.3166 5.90237 9.68342 6.29289 9.29289Z\" fill=\"currentColor\"/>
                                                  </svg>
                                              </div>

                                              <ul class=\"category-menu__children hidden\">
                                                  {% for subchild in child.children %}
                                                      <li class=\"category-menu__item\" data-id=\"{{ subchild.id }}\">
                                                          <div class=\"category-menu__item-left\">{{ subchild.name }}<span>{{ subchild.desc }}</span></div>
                                                      </li>
                                                  {% endfor %}
                                              </ul>
                                          {% endif %}
                                      </li>
                                  {% endfor %}
                              </ul>
                          {% endif %}
                      </li>
                  {% endif %}
              {% endfor %}
          </ul>
      </div>



      </div>{# bottom-bar__add-category-section #}

      <!-- Notes buttons -->
      <button type=\"button\" id=\"createNote\" class=\"button--round button--brand button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z\" fill=\"currentColor\"/></svg>
        Заметки — Создать
      </button>

      <button type=\"button\" id=\"addToNote\" class=\"button--round button--dark button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M21 6h-2v9H5v2h14c1.1 0 2-.9 2-2V6zM3 6v12c0 1.1.9 2 2 2h11v-2H5V6H3zm9-4l-2 2h4l-2-2z\" fill=\"currentColor\"/></svg>
        Добавить в заметку
      </button>

      <button type=\"submit\" id=\"createOperation\" class=\"button--round button--brand button--inset-shadow\">
        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path fill-rule=\"evenodd\" clip-rule=\"evenodd\" d=\"M0 11.75C0 5.26065 5.26065 0 11.75 0C18.2393 0 23.5 5.26065 23.5 11.75C23.5 18.2393 18.2393 23.5 11.75 23.5C5.26065 23.5 0 18.2393 0 11.75ZM11.75 16.5C11.3358 16.5 11 16.1642 11 15.75V12.5H7.75C7.33579 12.5 7 12.1642 7 11.75C7 11.3358 7.33579 11 7.75 11H11V7.75C11 7.33579 11.3358 7 11.75 7C12.1642 7 12.5 7.33579 12.5 7.75V11H15.75C16.1642 11 16.5 11.3358 16.5 11.75C16.5 12.1642 16.1642 12.5 15.75 12.5H12.5V15.75C12.5 16.1642 12.1642 16.5 11.75 16.5Z\" fill=\"currentColor\"/>
        </svg>
        Создать
      </button>
    {% endif %}

  </div>{# bottom-bar__button-box #}

</div>{# bottom-bar #}

{% endif %}", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\global\\bottom-bar.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1, "for" => 46];
        static $filters = ["escape" => 48, "length" => 51];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
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
