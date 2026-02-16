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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\partials\global\header.htm */
class __TwigTemplate_2ab16caa5e93db8a125a0dd92d370b9d extends Template
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
        yield "<header class=\"header\">

    <div class=\"header__left\">
        ";
        // line 4
        if ((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 4), "url", [], "any", false, false, true, 4) == "/add-operation")) {
            // line 5
            yield "            <a href=\"/\" class=\"header__logo\">InventPro</a>
        ";
        } else {
            // line 7
            yield "            <div class=\"header__logo\">InventPro</div>
        ";
        }
        // line 9
        yield "        ";
        if (((CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 9), "url", [], "any", false, false, true, 9) == "/warehouse") || (CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["this"] ?? null), "page", [], "any", false, false, true, 9), "url", [], "any", false, false, true, 9) == "/operation-history"))) {
            // line 10
            yield "            <div class=\"header-search form-floating\">
                <input type=\"text\" id=\"warehouse-search\" class=\"header-search__input form-input\" placeholder=\"Пошук товарів...\">
                <div class=\"header-search__icon\">
                    <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <path d=\"M20.25 11.5C20.25 6.66751 16.3325 2.75 11.5 2.75C6.66751 2.75 2.75 6.66751 2.75 11.5C2.75 16.3325 6.66751 20.25 11.5 20.25C16.3325 20.25 20.25 16.3325 20.25 11.5ZM21.75 11.5C21.75 17.1609 17.1609 21.75 11.5 21.75C5.83908 21.75 1.25 17.1609 1.25 11.5C1.25 5.83908 5.83908 1.25 11.5 1.25C17.1609 1.25 21.75 5.83908 21.75 11.5Z\" fill=\"currentColor\"/>
                        <path d=\"M19.4697 19.4697C19.7626 19.1768 20.2374 19.1768 20.5303 19.4697L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L19.4697 20.5303C19.1768 20.2374 19.1768 19.7626 19.4697 19.4697Z\" fill=\"currentColor\"/>
                    </svg>
                </div>";
            // line 18
            yield "                <div class=\"header-search__clear\" id=\"clearSearch\">
                    <svg width=\"18\" height=\"18\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <path d=\"M15.7123 7.22703C16.0052 6.93414 16.4801 6.93414 16.773 7.22703C17.0658 7.51992 17.0658 7.9948 16.773 8.28769L8.28767 16.773C7.99478 17.0659 7.5199 17.0659 7.22701 16.773C6.93412 16.4801 6.93412 16.0052 7.22701 15.7123L15.7123 7.22703Z\" fill=\"currentColor\"/>
                        <path d=\"M15.7123 16.773L7.22701 8.28769C6.93412 7.9948 6.93412 7.51992 7.22701 7.22703C7.5199 6.93414 7.99478 6.93414 8.28767 7.22703L16.773 15.7123C17.0658 16.0052 17.0658 16.4801 16.773 16.773C16.4801 17.0659 16.0052 17.0659 15.7123 16.773Z\" fill=\"currentColor\"/>
                    </svg>
                </div>";
            // line 24
            yield "            </div>";
            // line 25
            yield "        ";
        }
        // line 26
        yield "    </div>";
        // line 27
        yield "
    <div class=\"header__right\">
        <div class=\"header__buttons\">

            <div class=\"header__create\">

                <button id=\"headerCreateButton\" class=\"button button--nm button--secondary button--ico-right\">
                    Створити
                    <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <path d=\"M6.29289 9.29289C6.68342 8.90237 7.31658 8.90237 7.70711 9.29289L12 13.5858L16.2929 9.29289C16.6834 8.90237 17.3166 8.90237 17.7071 9.29289C18.0976 9.68342 18.0976 10.3166 17.7071 10.7071L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L6.29289 10.7071C5.90237 10.3166 5.90237 9.68342 6.29289 9.29289Z\" fill=\"currentColor\"/>
                    </svg>
                </button>

                <div class=\"header__create-dropdown dropdown\">
                    <a href=\"#\" id=\"openCreateNoteHeader\" class=\"header__create-item\">
                        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                            <path d=\"M11 19.75C11 20.1642 11.3358 20.5 11.75 20.5C12.1642 20.5 12.5 20.1642 12.5 19.75V12.5H19.75C20.1642 12.5 20.5 12.1642 20.5 11.75C20.5 11.3358 20.1642 11 19.75 11H12.5V3.75C12.5 3.33579 12.1642 3 11.75 3C11.3358 3 11 3.33579 11 3.75V11H3.75C3.33579 11 3 11.3358 3 11.75C3 12.1642 3.33579 12.5 3.75 12.5H11V19.75Z\" fill=\"currentColor\"/>
                        </svg>
                        Нотаток
                    </a>
                    <a href=\"/add-operation?type=приход\" class=\"header__create-item\">
                        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                            <path d=\"M11 19.75C11 20.1642 11.3358 20.5 11.75 20.5C12.1642 20.5 12.5 20.1642 12.5 19.75V12.5H19.75C20.1642 12.5 20.5 12.1642 20.5 11.75C20.5 11.3358 20.1642 11 19.75 11H12.5V3.75C12.5 3.33579 12.1642 3 11.75 3C11.3358 3 11 3.33579 11 3.75V11H3.75C3.33579 11 3 11.3358 3 11.75C3 12.1642 3.33579 12.5 3.75 12.5H11V19.75Z\" fill=\"currentColor\"/>
                        </svg>
                        Приход
                    </a>
                </div>";
        // line 54
        yield "
            </div>";
        // line 56
        yield "
            <form id=\"importForm\" data-request=\"onImportExcel\" enctype=\"multipart/form-data\" data-request-files>
                <input id=\"importInput\" style=\"display: none\" type=\"file\" name=\"excel_file\" accept=\".xlsx,.xls,.csv\" required>
                <button id=\"importButton\" type=\"button\" class=\"button button--nm button--brand button--ico-left\">
                <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M6.70711 9.29289L11 13.5858V3C11 2.44772 11.4477 2 12 2C12.5523 2 13 2.44772 13 3V13.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.5196 16.8946 12.2652 17 12 17C11.7348 17 11.4804 16.8946 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289Z\" fill=\"currentColor\"/>
                    <path d=\"M21 20C21.5523 20 22 20.4477 22 21C22 21.5523 21.5523 22 21 22H3C2.44772 22 2 21.5523 2 21C2 20.4477 2.44772 20 3 20H21Z\" fill=\"currentColor\"/>
                </svg>
                    Імпорт
                </button>
            </form>
        </div>";
        // line 68
        yield "
        <div class=\"header__profile\">
            <div class=\"header__profile-avatar\">
                <img src=\"";
        // line 71
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "avatar", [], "any", false, false, true, 71), "path", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
        yield "\" alt=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "name", [], "any", false, false, true, 71), 71, $this->source), "html", null, true);
        yield "\">
            </div>
        </div>

    </div>";
        // line 76
        yield "</header>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\global\\header.htm";
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
        return array (  143 => 76,  134 => 71,  129 => 68,  116 => 56,  113 => 54,  85 => 27,  83 => 26,  80 => 25,  78 => 24,  71 => 18,  62 => 10,  59 => 9,  55 => 7,  51 => 5,  49 => 4,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<header class=\"header\">

    <div class=\"header__left\">
        {% if this.page.url == '/add-operation' %}
            <a href=\"/\" class=\"header__logo\">InventPro</a>
        {% else %}
            <div class=\"header__logo\">InventPro</div>
        {% endif %}
        {% if this.page.url == '/warehouse' or this.page.url == '/operation-history'  %}
            <div class=\"header-search form-floating\">
                <input type=\"text\" id=\"warehouse-search\" class=\"header-search__input form-input\" placeholder=\"Пошук товарів...\">
                <div class=\"header-search__icon\">
                    <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <path d=\"M20.25 11.5C20.25 6.66751 16.3325 2.75 11.5 2.75C6.66751 2.75 2.75 6.66751 2.75 11.5C2.75 16.3325 6.66751 20.25 11.5 20.25C16.3325 20.25 20.25 16.3325 20.25 11.5ZM21.75 11.5C21.75 17.1609 17.1609 21.75 11.5 21.75C5.83908 21.75 1.25 17.1609 1.25 11.5C1.25 5.83908 5.83908 1.25 11.5 1.25C17.1609 1.25 21.75 5.83908 21.75 11.5Z\" fill=\"currentColor\"/>
                        <path d=\"M19.4697 19.4697C19.7626 19.1768 20.2374 19.1768 20.5303 19.4697L22.5303 21.4697C22.8232 21.7626 22.8232 22.2374 22.5303 22.5303C22.2374 22.8232 21.7626 22.8232 21.4697 22.5303L19.4697 20.5303C19.1768 20.2374 19.1768 19.7626 19.4697 19.4697Z\" fill=\"currentColor\"/>
                    </svg>
                </div>{# header-search__icon #}
                <div class=\"header-search__clear\" id=\"clearSearch\">
                    <svg width=\"18\" height=\"18\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <path d=\"M15.7123 7.22703C16.0052 6.93414 16.4801 6.93414 16.773 7.22703C17.0658 7.51992 17.0658 7.9948 16.773 8.28769L8.28767 16.773C7.99478 17.0659 7.5199 17.0659 7.22701 16.773C6.93412 16.4801 6.93412 16.0052 7.22701 15.7123L15.7123 7.22703Z\" fill=\"currentColor\"/>
                        <path d=\"M15.7123 16.773L7.22701 8.28769C6.93412 7.9948 6.93412 7.51992 7.22701 7.22703C7.5199 6.93414 7.99478 6.93414 8.28767 7.22703L16.773 15.7123C17.0658 16.0052 17.0658 16.4801 16.773 16.773C16.4801 17.0659 16.0052 17.0659 15.7123 16.773Z\" fill=\"currentColor\"/>
                    </svg>
                </div>{# header-search__clear #}
            </div>{# header-search form-floating #}
        {% endif %}
    </div>{# header__left #}

    <div class=\"header__right\">
        <div class=\"header__buttons\">

            <div class=\"header__create\">

                <button id=\"headerCreateButton\" class=\"button button--nm button--secondary button--ico-right\">
                    Створити
                    <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <path d=\"M6.29289 9.29289C6.68342 8.90237 7.31658 8.90237 7.70711 9.29289L12 13.5858L16.2929 9.29289C16.6834 8.90237 17.3166 8.90237 17.7071 9.29289C18.0976 9.68342 18.0976 10.3166 17.7071 10.7071L12.7071 15.7071C12.3166 16.0976 11.6834 16.0976 11.2929 15.7071L6.29289 10.7071C5.90237 10.3166 5.90237 9.68342 6.29289 9.29289Z\" fill=\"currentColor\"/>
                    </svg>
                </button>

                <div class=\"header__create-dropdown dropdown\">
                    <a href=\"#\" id=\"openCreateNoteHeader\" class=\"header__create-item\">
                        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                            <path d=\"M11 19.75C11 20.1642 11.3358 20.5 11.75 20.5C12.1642 20.5 12.5 20.1642 12.5 19.75V12.5H19.75C20.1642 12.5 20.5 12.1642 20.5 11.75C20.5 11.3358 20.1642 11 19.75 11H12.5V3.75C12.5 3.33579 12.1642 3 11.75 3C11.3358 3 11 3.33579 11 3.75V11H3.75C3.33579 11 3 11.3358 3 11.75C3 12.1642 3.33579 12.5 3.75 12.5H11V19.75Z\" fill=\"currentColor\"/>
                        </svg>
                        Нотаток
                    </a>
                    <a href=\"/add-operation?type=приход\" class=\"header__create-item\">
                        <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                            <path d=\"M11 19.75C11 20.1642 11.3358 20.5 11.75 20.5C12.1642 20.5 12.5 20.1642 12.5 19.75V12.5H19.75C20.1642 12.5 20.5 12.1642 20.5 11.75C20.5 11.3358 20.1642 11 19.75 11H12.5V3.75C12.5 3.33579 12.1642 3 11.75 3C11.3358 3 11 3.33579 11 3.75V11H3.75C3.33579 11 3 11.3358 3 11.75C3 12.1642 3.33579 12.5 3.75 12.5H11V19.75Z\" fill=\"currentColor\"/>
                        </svg>
                        Приход
                    </a>
                </div>{# header__button-create-dropdown #}

            </div>{# header__button-create #}

            <form id=\"importForm\" data-request=\"onImportExcel\" enctype=\"multipart/form-data\" data-request-files>
                <input id=\"importInput\" style=\"display: none\" type=\"file\" name=\"excel_file\" accept=\".xlsx,.xls,.csv\" required>
                <button id=\"importButton\" type=\"button\" class=\"button button--nm button--brand button--ico-left\">
                <svg width=\"16\" height=\"16\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <path d=\"M6.70711 9.29289L11 13.5858V3C11 2.44772 11.4477 2 12 2C12.5523 2 13 2.44772 13 3V13.5858L17.2929 9.29289C17.6834 8.90237 18.3166 8.90237 18.7071 9.29289C19.0976 9.68342 19.0976 10.3166 18.7071 10.7071L12.7071 16.7071C12.5196 16.8946 12.2652 17 12 17C11.7348 17 11.4804 16.8946 11.2929 16.7071L5.29289 10.7071C4.90237 10.3166 4.90237 9.68342 5.29289 9.29289C5.68342 8.90237 6.31658 8.90237 6.70711 9.29289Z\" fill=\"currentColor\"/>
                    <path d=\"M21 20C21.5523 20 22 20.4477 22 21C22 21.5523 21.5523 22 21 22H3C2.44772 22 2 21.5523 2 21C2 20.4477 2.44772 20 3 20H21Z\" fill=\"currentColor\"/>
                </svg>
                    Імпорт
                </button>
            </form>
        </div>{# header__button-box #}

        <div class=\"header__profile\">
            <div class=\"header__profile-avatar\">
                <img src=\"{{ user.avatar.path }}\" alt=\"{{ user.name }}\">
            </div>
        </div>

    </div>{# header__right #}
</header>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\global\\header.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 4];
        static $filters = ["escape" => 71];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
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
