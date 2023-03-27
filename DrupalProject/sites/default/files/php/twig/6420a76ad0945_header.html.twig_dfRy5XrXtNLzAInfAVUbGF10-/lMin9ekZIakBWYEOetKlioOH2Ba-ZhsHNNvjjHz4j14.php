<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* @xara/template-parts/header.html.twig */
class __TwigTemplate_d55109452b90969f713e2a3eb3f02ecd extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<header class=\"header\">
";
        // line 2
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_left", [], "any", false, false, true, 2) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header_top_right", [], "any", false, false, true, 2))) {
            // line 3
            echo "  <div class=\"header-top\">
    <div class=\"container\">
      <div class=\"header-top-container\">
        ";
            // line 6
            $this->loadTemplate("@thex/template-parts/header/header-top-left.html.twig", "@xara/template-parts/header.html.twig", 6)->display($context);
            // line 7
            echo "        ";
            $this->loadTemplate("@thex/template-parts/header/header-top-right.html.twig", "@xara/template-parts/header.html.twig", 7)->display($context);
            // line 8
            echo "      </div><!-- /header-top-container -->
    </div><!-- /container -->
  </div><!-- /header-top -->
";
        }
        // line 12
        echo "<div class=\"header-main\">
  <div class=\"container\">
    <div class=\"header-container\">
      ";
        // line 15
        if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 15)) {
            // line 16
            echo "        <div class=\"site-brand\">
          ";
            // line 17
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "header", [], "any", false, false, true, 17), 17, $this->source), "html", null, true);
            echo "
        </div> <!--/.site-branding -->
      ";
        }
        // line 20
        echo "      ";
        if ((twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 20) || twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "search_box", [], "any", false, false, true, 20))) {
            // line 21
            echo "      <div class=\"header-right\">
        ";
            // line 22
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 22)) {
                // line 23
                echo "          <div class=\"mobile-menu\"><span></span></div> ";
                // line 24
                echo "          <div class=\"primary-menu-wrapper\">
            <div class=\"menu-wrap\">
              <div class=\"close-mobile-menu\">x</div>
              ";
                // line 27
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "primary_menu", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
                echo "
            </div> <!-- /.menu-wrap -->
          </div> <!-- /.primary-menu-wrapper -->
        ";
            }
            // line 30
            echo " <!--  end if for -->
        ";
            // line 31
            if (twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "search_box", [], "any", false, false, true, 31)) {
                // line 32
                echo "          ";
                $this->loadTemplate("@thex/template-parts/header/search.html.twig", "@xara/template-parts/header.html.twig", 32)->display($context);
                // line 33
                echo "        ";
            }
            // line 34
            echo "      </div> <!-- /.header-right -->
    ";
        }
        // line 36
        echo "    </div><!-- /header-container -->
  </div><!-- /container -->
</div><!-- /header main -->
";
        // line 39
        if ((($context["is_front"] ?? null) && ($context["slider_show"] ?? null))) {
            // line 40
            echo "  ";
            $this->loadTemplate("@xara/template-parts/slider.html.twig", "@xara/template-parts/header.html.twig", 40)->display($context);
        } elseif (( !        // line 41
($context["is_front"] ?? null) && twig_get_attribute($this->env, $this->source, ($context["page"] ?? null), "page_header", [], "any", false, false, true, 41))) {
            // line 42
            echo "  ";
            $this->loadTemplate("@thex/template-parts/header/header-page.html.twig", "@xara/template-parts/header.html.twig", 42)->display($context);
        }
        // line 44
        echo "</header>";
    }

    public function getTemplateName()
    {
        return "@xara/template-parts/header.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  129 => 44,  125 => 42,  123 => 41,  120 => 40,  118 => 39,  113 => 36,  109 => 34,  106 => 33,  103 => 32,  101 => 31,  98 => 30,  91 => 27,  86 => 24,  84 => 23,  82 => 22,  79 => 21,  76 => 20,  70 => 17,  67 => 16,  65 => 15,  60 => 12,  54 => 8,  51 => 7,  49 => 6,  44 => 3,  42 => 2,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "@xara/template-parts/header.html.twig", "C:\\xampp\\htdocs\\DrupalProject\\themes\\xara\\templates\\template-parts\\header.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 2, "include" => 6);
        static $filters = array("escape" => 17);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['if', 'include'],
                ['escape'],
                []
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
