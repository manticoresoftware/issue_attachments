index excerpt
{
    type          = template
    morphology           = lemmatize_ru_all
    source               = excerpt_source
    charset_table        = <?=$charset_table?>

    blend_chars          = <?=$blend_chars?>

    ngram_chars          = <?=$ngram_chars?>

    ngram_len            = 1
    html_strip           = 1
    html_remove_elements = style, script
}

index excerpt_no_html_strip
{
    type          = template
    morphology    = lemmatize_ru_all
    source        = excerpt_source
    charset_table = <?=$charset_table?>

    blend_chars   = <?=$blend_chars?>

    ngram_chars   = <?=$ngram_chars?>

    ngram_len     = 1
    html_strip    = 0
}

index excerpt_none_no_html_strip
{
    type          = template
    morphology    = none

    charset_table = <?=$charset_table?>

    blend_chars   = <?=$blend_chars?>

    ngram_chars   = <?=$ngram_chars?>

    ngram_len     = 1
    html_strip    = 0
}

index excerpt_en_no_html_strip : excerpt_none_no_html_strip
{
    type          = template
    morphology    = lemmatize_en_all
}

index excerpt_ru_no_html_strip : excerpt_none_no_html_strip
{
    type          = template
    morphology    = lemmatize_ru_all, lemmatize_en_all
}


index excerpt_none : excerpt_none_no_html_strip
{
    type                 = template
    html_strip           = 1
    html_remove_elements = style, script
}

index excerpt_ru : excerpt_none
{
    type                 = template
    morphology           = lemmatize_ru_all, lemmatize_en_all
}

index excerpt_en : excerpt_none
{
    type                 = template
    morphology           = lemmatize_en_all
}
