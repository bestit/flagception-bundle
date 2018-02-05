Twig
-------------------------
You can check the feature flag state with the following twig methods.

Simple check:
```twig
{% if feature('feature_123') %}
    {# ... #}
{% endif %}
```

Same check with other syntax:
```twig
{% if 'feature_123' is active feature %}
    {# ... #}
{% endif %}
```

Check with context data (see [constraint documentation](constraint.md))
```twig
{% if feature('feature_123', {'role': 'ROLE_ADMIN'}) %}
    {# ... #}
{% endif %}
```
