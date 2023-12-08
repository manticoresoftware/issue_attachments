<?php
include_once(__DIR__ . '/charset/charsetTable.php');
$stopwords = file_get_contents(__DIR__ . '/synonyms/stops.txt');
$exceptions = file_get_contents(__DIR__ . '/synonyms/synonyms.txt');
return [
    'language'   => 'custom',
    'rt_include' => [
        'index_token_filter'   => 'blended.so:blended',
        'charset_table'        => $charset_table . ',' . $ngram_chars,
        'ngram_chars'          => $charset_table_emoji,
        'ngram_len'            => '1',
        'html_strip'           => '1',
        'index_sp'             => '1',
        'html_remove_elements' => 'style, script',
        'stopword_step'        => '0',
    ],
    'stopwords'  => $stopwords,
    'exceptions' => $exceptions,
    'source'     => 'reviews_pq_combined_stream_ms',
    'attributes' => [
        ["name" => "json", "path" => "whole_document", "type" => "json"],

        // @title - Review title
        ["name" => "title", "path" => "review.subject", "type" => "string"],

        // @text - Review text
        ["name" => "text", "path" => "review.text", "type" => "string"],

        // @pros - Review Pros text
        ["name" => "pros", "path" => "review.textPros", "type" => "string"],

        // @cons - Review Cons text
        ["name" => "cons", "path" => "review.textCons", "type" => "string"],

        // @item - Item name
        ["name" => "item", "path" => "review.item", "type" => "string"],

        // item_url=
        ["name" => "item_url", "path" => "review.itemURL", "type" => "url"],

        // dn=
        ["name" => "site_alias", "path" => "review.siteAlias", "type" => "url"],
    ],
    'jslt'       => 'let object = .review

let published = $object.reviewDate
let crawled = $object.crawled

def getRatings(obj)
  let list = {for ($obj) "Rating@name=" + .key : number(.value, 3)}
  if ($list) $list
  else ""

{
  "Id": if ($object.id) $object.id else "",
  "Title": if ($object.subject) $object.subject else "",
  "Text": if ($object.text) $object.text else "",
  "TextPros": if ($object.textPros) $object.textPros else "",
  "TextCons": if ($object.textCons) $object.textCons else "",
  "TitleHtml": if ($object.subject) $object.subject else "",
  "TextHtml": if ($object.text) $object.text else "",
  "TextProsHtml": if ($object.textPros) $object.textPros else "",
  "TextConsHtml": if ($object.textCons) $object.textCons else "",
  "AttributeRatings": getRatings($object.attributeRatings),
  "ReviewRating": if ($object.reviewRating.ratingNum) number($object.reviewRating.ratingNum, 0) else "",
  "Recommend": if ($object.recommend) $object.recommend else "",
  "ReviewType": if ($object.reviewType) $object.reviewType else "",
  "Published": if ($published) $published else "",
  "Inserted": if ($crawled) $crawled else "",
  "Crawled": if ($crawled) $crawled else "",
  "AuthorInfo": {
    "Id": "",
    "Url": if ($object.authorURL) $object.authorURL else "",
    "UserName": if ($object.authorName) $object.authorName else "",
    "Name": if ($object.author) $object.author else "",
    "AvatarUrl": if ($object.avatarURL) $object.avatarURL else "",
    "Location": if ($object.location) $object.location else "",
    "Age": if ($object.age) $object.age else "",
    "Sex": if ($object.sex) $object.sex else "",
    "Description": if ($object.authorDetails) $object.authorDetails else ""
  },
  "Url": if ($object.url) $object.url else "",
  "OriginalSiteUrl": if ($object.origSiteURL) $object.origSiteURL else "",
  "OriginalUrl": if ($object.origURL) $object.origURL else "",
  "Language": if ($object.language) $object.language else "",
  "SiteInfo": {
    "Id": "",
    "ExtKey": if ($object.siteID) $object.siteID else "",
    "Name": if ($object.siteAlias) $object.siteAlias else "",
    "SiteUrl": if ($object.siteURL) $object.siteURL else "",
    "Country": if ($object.siteCountry) $object.siteCountry else ""
  },
  "ItemInfo": {
    "Id": "",
    "Item": if ($object.item) $object.item else "",
    "Category": if ($object.itemCategory) $object.itemCategory else "",
    "CategoryUrl": if ($object.itemCategoryURL) $object.itemCategoryURL else "",
    "ItemUrl": if ($object.itemURL) $object.itemURL else "",
    "ItemHtml": if ($object.item) $object.item else "",
    "CategoryHtml": if ($object.itemCategory) $object.itemCategory else ""
  },
  "Reference": {
    "RequestId": if ($object.reference.requestId) $object.reference.requestId else "",
    "ProjectId": if ($object.reference.projectId) $object.reference.projectId else "",
    "ReviewProduct": if ($object.reference.reviewProduct) $object.reference.reviewProduct else "",
    "Origin": if ($object.reference.origin) $object.reference.origin else ""
  },
  "PostSize": if ($object.item) size($object.item) else 0,
  "ContentSource": "Reviews/Search",
  "MatchingQueries": .matching_queries,
}',
];
