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

/* core/themes/claro/templates/classy/content/comment.html.twig */
class __TwigTemplate_739d3149515210adf6181467cc2022c5 extends \Twig\Template
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
        // line 69
        if (($context["threaded"] ?? null)) {
            // line 70
            echo "  ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->extensions['Drupal\Core\Template\TwigExtension']->attachLibrary("claro/classy.indented"), "html", null, true);
            echo "
";
        }
        // line 73
        $context["classes"] = [0 => "comment", 1 => "js-comment", 2 => (((        // line 76
($context["status"] ?? null) != "published")) ? (($context["status"] ?? null)) : ("")), 3 => ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 77
($context["comment"] ?? null), "owner", [], "any", false, false, true, 77), "anonymous", [], "any", false, false, true, 77)) ? ("by-anonymous") : ("")), 4 => (((        // line 78
($context["author_id"] ?? null) && (($context["author_id"] ?? null) == twig_get_attribute($this->env, $this->source, ($context["commented_entity"] ?? null), "getOwnerId", [], "method", false, false, true, 78)))) ? ((("by-" . $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["commented_entity"] ?? null), "getEntityTypeId", [], "method", false, false, true, 78), 78, $this->source)) . "-author")) : (""))];
        // line 81
        echo "<article";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => ($context["classes"] ?? null)], "method", false, false, true, 81), 81, $this->source), "html", null, true);
        echo ">
  ";
        // line 87
        echo "  <mark class=\"hidden\" data-comment-timestamp=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["new_indicator_timestamp"] ?? null), 87, $this->source), "html", null, true);
        echo "\"></mark>

  ";
        // line 89
        if (($context["submitted"] ?? null)) {
            // line 90
            echo "    <footer class=\"comment__meta\">
      ";
            // line 91
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_picture"] ?? null), 91, $this->source), "html", null, true);
            echo "
      <p class=\"comment__submitted\">";
            // line 92
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["submitted"] ?? null), 92, $this->source), "html", null, true);
            echo "</p>

      ";
            // line 99
            echo "      ";
            if (($context["parent"] ?? null)) {
                // line 100
                echo "        <p class=\"parent visually-hidden\">";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["parent"] ?? null), 100, $this->source), "html", null, true);
                echo "</p>
      ";
            }
            // line 102
            echo "
      ";
            // line 103
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["permalink"] ?? null), 103, $this->source), "html", null, true);
            echo "
    </footer>
  ";
        }
        // line 106
        echo "
  <div";
        // line 107
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => "content"], "method", false, false, true, 107), 107, $this->source), "html", null, true);
        echo ">
    ";
        // line 108
        if (($context["title"] ?? null)) {
            // line 109
            echo "      ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 109, $this->source), "html", null, true);
            echo "
      <h3";
            // line 110
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_attributes"] ?? null), 110, $this->source), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 110, $this->source), "html", null, true);
            echo "</h3>
      ";
            // line 111
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 111, $this->source), "html", null, true);
            echo "
    ";
        }
        // line 113
        echo "    ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 113, $this->source), "html", null, true);
        echo "
  </div>
</article>
";
    }

    public function getTemplateName()
    {
        return "core/themes/claro/templates/classy/content/comment.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  120 => 113,  115 => 111,  109 => 110,  104 => 109,  102 => 108,  98 => 107,  95 => 106,  89 => 103,  86 => 102,  80 => 100,  77 => 99,  72 => 92,  68 => 91,  65 => 90,  63 => 89,  57 => 87,  52 => 81,  50 => 78,  49 => 77,  48 => 76,  47 => 73,  41 => 70,  39 => 69,);
    }

    public function getSourceContext()
    {
        return new Source("", "core/themes/claro/templates/classy/content/comment.html.twig", "C:\\xampp\\htdocs\\DrupalProject\\core\\themes\\claro\\templates\\classy\\content\\comment.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("if" => 69, "set" => 73);
        static $filters = array("escape" => 70);
        static $functions = array("attach_library" => 70);

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set'],
                ['escape'],
                ['attach_library']
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
