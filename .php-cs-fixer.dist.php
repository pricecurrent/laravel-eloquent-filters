<?php

$finder = Symfony\Component\Finder\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        'blank_line_after_namespace' => true,
        'braces' => true,
        'class_definition' => true,
        'constant_case' => true,
        'elseif' => true,
        'encoding' => true,
        'full_opening_tag' => true,
        'function_declaration' => true,
        'indentation_type' => true,
        'line_ending' => true,
        'lowercase_keywords' => true,
        'method_argument_space' => true,
        'no_break_comment' => true,
        'no_closing_tag' => true,
        'no_spaces_after_function_name' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'single_blank_line_at_eof' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_after_imports' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'visibility_required' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_trailing_comma_in_singleline_array' => true,
        'array_indentation' => true,
        'blank_line_before_statement' => true,
        'method_chaining_indentation' => true,
        'no_extra_blank_lines' => true,
        'no_spaces_around_offset' => true,
        'no_whitespace_in_blank_line' => true,
        'types_spaces' => [
            'space' => 'single',
        ],
        'braces' => true,
        'static_lambda' => false,
        'lambda_not_used_import' => true, // removes `use ($var)` in a closure if $var is not used inside the closure
        'function_typehint_space' => true,
        'return_type_declaration' => [
            'space_before' => 'none',
        ],
        'single_line_throw' => true,
        'global_namespace_import' => [
            'import_classes' => true,
        ],
        'no_unused_imports' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],
        'single_blank_line_before_namespace' => true,
        'multiline_whitespace_before_semicolons' => true,
        'semicolon_after_instruction' => true,
        'space_after_semicolon' => true,
        'no_useless_return' => true,
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'php_unit_fqcn_annotation' => true,
        'single_space_after_construct' => true,
        'binary_operator_spaces' => true,
        'concat_space' => ['spacing' => 'one'],
        'new_with_braces' => true,
        'not_operator_with_successor_space' => true,
        'object_operator_without_whitespace' => true,
        'operator_linebreak' => true,
        'ternary_operator_spaces' => true,
        'ternary_to_null_coalescing' => true,
        'unary_operator_spaces' => true,
    ])
    ->setFinder($finder);
