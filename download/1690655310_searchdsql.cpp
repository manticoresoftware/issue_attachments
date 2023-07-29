





<!DOCTYPE html>
<html class="with-top-bar " lang="en">
<head prefix="og: http://ogp.me/ns#">
<meta charset="utf-8">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
<title>src/searchdsql.cpp · master · manticoresearch / Manticore Search · GitLab</title>
<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
window.gon={};gon.features={"highlightJsWorker":false,"explainCodeChat":false,"remoteDevelopmentFeatureFlag":true};gon.licensed_features={"remoteDevelopment":true};
//]]>
</script>
<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
window.uploads_path = "/manticoresearch/dev/uploads";



//]]>
</script>
<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
var gl = window.gl || {};
gl.startup_calls = null;
gl.startup_graphql_calls = [{"query":"query getBlobInfo(\n  $projectPath: ID!\n  $filePath: String!\n  $ref: String!\n  $refType: RefType\n  $shouldFetchRawText: Boolean!\n) {\n  project(fullPath: $projectPath) {\n    __typename\n    id\n    repository {\n      __typename\n      empty\n      blobs(paths: [$filePath], ref: $ref, refType: $refType) {\n        __typename\n        nodes {\n          __typename\n          id\n          webPath\n          name\n          size\n          rawSize\n          rawTextBlob @include(if: $shouldFetchRawText)\n          fileType\n          language\n          path\n          blamePath\n          editBlobPath\n          gitpodBlobUrl\n          ideEditPath\n          forkAndEditPath\n          ideForkAndEditPath\n          codeNavigationPath\n          projectBlobPathRoot\n          forkAndViewPath\n          environmentFormattedExternalUrl\n          environmentExternalUrlForRouteMap\n          canModifyBlob\n          canCurrentUserPushToBranch\n          archived\n          storedExternally\n          externalStorage\n          externalStorageUrl\n          rawPath\n          replacePath\n          pipelineEditorPath\n          simpleViewer {\n            fileType\n            tooLarge\n            type\n            renderError\n          }\n          richViewer {\n            fileType\n            tooLarge\n            type\n            renderError\n          }\n        }\n      }\n    }\n  }\n}\n","variables":{"projectPath":"manticoresearch/dev","ref":"master","refType":"","filePath":"src/searchdsql.cpp","shouldFetchRawText":true}}];

if (gl.startup_calls && window.fetch) {
  Object.keys(gl.startup_calls).forEach(apiCall => {
   gl.startup_calls[apiCall] = {
      fetchCall: fetch(apiCall, {
        // Emulate XHR for Rails AJAX request checks
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        },
        // fetch won’t send cookies in older browsers, unless you set the credentials init option.
        // We set to `same-origin` which is default value in modern browsers.
        // See https://github.com/whatwg/fetch/pull/585 for more information.
        credentials: 'same-origin'
      })
    };
  });
}
if (gl.startup_graphql_calls && window.fetch) {
  const headers = {"X-CSRF-Token":"fprNnmbJNFBmylVDAjYQYFtwTcV9y3KxArdfEQl0vOQcavHeXezTKYi-B3w40YTeoKtr2sqY61ChqTLRYL2Sdg","x-gitlab-feature-category":"source_code_management"};
  const url = `https://gitlab.com/api/graphql`

  const opts = {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      ...headers,
    }
  };

  gl.startup_graphql_calls = gl.startup_graphql_calls.map(call => ({
    ...call,
    fetchCall: fetch(url, {
      ...opts,
      credentials: 'same-origin',
      body: JSON.stringify(call)
    })
  }))
}


//]]>
</script>

<link rel="prefetch" href="/assets/webpack/monaco.3dbb5db2.chunk.js">
<link rel="stylesheet" href="/assets/themes/theme_indigo-3331cd49e4ca5527df9ebb4ec47e8d1463168d5a75df83bf264da79f38af4c96.css" />

<link rel="stylesheet" href="/assets/application-8a7e00570a477b8baa173d87124dd9e308fbe082cc283742f5daa49441f01c3c.css" media="all" />
<link rel="stylesheet" href="/assets/page_bundles/tree-7eb97b0bf23760737864cc9c5cd5c9f23c3de24178d1b9393cea9f64caca3bcd.css" media="all" />
<link rel="stylesheet" href="/assets/application_utilities-de29fbf7d8a2b158eb7ee5a047460444de87ebc965da75055432d930a3d29dde.css" media="all" />


<link rel="stylesheet" href="/assets/fonts-171e1863d044918ea3bbaacf2a559ccaac603904aa851c3add5b714fa7066468.css" media="all" />
<link rel="stylesheet" href="/assets/highlight/themes/white-985309ef234225cdebce383a5d2120436ba68d44ff973791e248da69f62f3e5a.css" media="all" />

<script src="/assets/webpack/runtime.432a01ad.bundle.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/main.58291680.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/tracker.cc67efc7.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
window.snowplowOptions = {"namespace":"gl","hostname":"snowplow.trx.gitlab.net","cookieDomain":".gitlab.com","appId":"gitlab","formTracking":true,"linkClickTracking":true}

gl = window.gl || {};
gl.snowplowStandardContext = {"schema":"iglu:com.gitlab/gitlab_standard/jsonschema/1-0-9","data":{"environment":"production","source":"gitlab-rails","plan":"free","extra":{"new_nav":true},"user_id":2801001,"namespace_id":1843808,"project_id":3858465,"context_generated_at":"2023-07-29T18:28:30.739Z"}}
gl.snowplowPseudonymizedPageUrl = "https://gitlab.com/namespace1843808/project3858465/-/blob/:repository_path";


//]]>
</script>
<link rel="preload" href="/assets/application_utilities-de29fbf7d8a2b158eb7ee5a047460444de87ebc965da75055432d930a3d29dde.css" as="style" type="text/css" nonce="u52IdiEE9+4rwC5lzBl6uw==">
<link rel="preload" href="/assets/application-8a7e00570a477b8baa173d87124dd9e308fbe082cc283742f5daa49441f01c3c.css" as="style" type="text/css" nonce="u52IdiEE9+4rwC5lzBl6uw==">
<link rel="preload" href="/assets/highlight/themes/white-985309ef234225cdebce383a5d2120436ba68d44ff973791e248da69f62f3e5a.css" as="style" type="text/css" nonce="u52IdiEE9+4rwC5lzBl6uw==">
<link crossorigin="" href="https://snowplow.trx.gitlab.net" rel="preconnect">
<link as="font" crossorigin="" href="/assets/gitlab-sans/GitLabSans-1e0a5107ea3bbd4be93e8ad2c503467e43166cd37e4293570b490e0812ede98b.woff2" rel="preload">
<link as="font" crossorigin="" href="/assets/gitlab-mono/GitLabMono-08d2c5e8ff8fd3d2d6ec55bc7713380f8981c35f9d2df14e12b835464d6e8f23.woff2" rel="preload">
<link as="font" crossorigin="" href="/assets/gitlab-mono/GitLabMono-Italic-38e58d8df29485a20c550da1d0111e2c2169f6dcbcf894f2cd3afbdd97bcc588.woff2" rel="preload">
<link rel="preload" href="/assets/fonts-171e1863d044918ea3bbaacf2a559ccaac603904aa851c3add5b714fa7066468.css" as="style" type="text/css" nonce="u52IdiEE9+4rwC5lzBl6uw==">



<script src="/assets/webpack/sentry.1b8f7276.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>


<script src="/assets/webpack/commons-pages.groups.new-pages.import.gitlab_projects.new-pages.import.manifest.new-pages.projects.n-4e5d09f9.b0f2b3d4.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.search.show-super_sidebar.14a11192.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/super_sidebar.6afbac7f.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/shortcutsBundle.90dbf4b4.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.groups.boards-pages.groups.details-pages.groups.epic_boards-pages.groups.show-pages.gr-71149389.139e8730.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.admin.runners.show-pages.groups.achievements-pages.groups.analytics.dashboards-pages.g-9e65c5d2.cf1e6259.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.projects.blob.show-pages.projects.show-pages.projects.snippets.edit-pages.projects.sni-dd84f7c7.bddf585c.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.projects.blob.show-pages.projects.show-pages.projects.snippets.show-pages.projects.tre-25c821a4.15d0c9e9.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.projects.blob.show-pages.projects.shared.web_ide_link-pages.projects.show-pages.projec-6acd8882.3ec80375.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.groups.show-pages.projects.blob.show-pages.projects.show-pages.projects.tree.show.a59eded5.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.projects.blob.show-pages.projects.shared.web_ide_link-pages.projects.show-pages.projec-be9a6d69.bc2a8288.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.projects.blob.show-pages.projects.show-pages.projects.tree.show.6f2c0397.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/commons-pages.projects.blob.show-pages.projects.tree.show-treeList.2adde726.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<script src="/assets/webpack/pages.projects.blob.show.0a685b9f.chunk.js" defer="defer" nonce="B6eizRq8nHhnUsADWqyHRQ=="></script>
<meta content="object" property="og:type">
<meta content="GitLab" property="og:site_name">
<meta content="src/searchdsql.cpp · master · manticoresearch / Manticore Search · GitLab" property="og:title">
<meta content="Manticore Search Engine" property="og:description">
<meta content="https://gitlab.com/uploads/-/system/project/avatar/3858465/download.jpg" property="og:image">
<meta content="64" property="og:image:width">
<meta content="64" property="og:image:height">
<meta content="https://gitlab.com/manticoresearch/dev/-/blob/master/src/searchdsql.cpp" property="og:url">
<meta content="summary" property="twitter:card">
<meta content="src/searchdsql.cpp · master · manticoresearch / Manticore Search · GitLab" property="twitter:title">
<meta content="Manticore Search Engine" property="twitter:description">
<meta content="https://gitlab.com/uploads/-/system/project/avatar/3858465/download.jpg" property="twitter:image">

<meta name="csrf-param" content="authenticity_token" />
<meta name="csrf-token" content="iXTuKe_ErPhjUIwz5PdYK5-K28nzWq_E5xFEDvXasUHrhNJp1OFLgY0k3gzeEMyVZFH91kQJNiVEDynOnBOf0w" />
<meta name="csp-nonce" content="B6eizRq8nHhnUsADWqyHRQ==" />
<meta name="action-cable-url" content="/-/cable" />
<link href="/-/manifest.json" rel="manifest">
<link rel="icon" type="image/png" href="/assets/favicon-72a2cad5025aa931d6ea56c3201d1f18e68a8cd39788c7c80d5b2b82aa5143ef.png" id="favicon" data-original-href="/assets/favicon-72a2cad5025aa931d6ea56c3201d1f18e68a8cd39788c7c80d5b2b82aa5143ef.png" />
<link rel="apple-touch-icon" type="image/x-icon" href="/assets/apple-touch-icon-b049d4bc0dd9626f31db825d61880737befc7835982586d015bded10b4435460.png" />
<link href="/search/opensearch.xml" rel="search" title="Search GitLab" type="application/opensearchdescription+xml">




<meta content="Manticore Search Engine" name="description">
<meta content="#292961" name="theme-color">
</head>

<body class="ui-indigo tab-width-8 gl-browser-generic gl-platform-other" data-find-file="/manticoresearch/dev/-/find_file/master" data-group="manticoresearch" data-group-full-path="manticoresearch" data-namespace-id="1843808" data-page="projects:blob:show" data-page-type-id="master/src/searchdsql.cpp" data-project="dev" data-project-id="3858465">

<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
gl = window.gl || {};
gl.client = {"isGeneric":true,"isOther":true};


//]]>
</script>



<style>
  body {
    --header-height: 0px;
  }
</style>
<div class="layout-page hide-when-top-nav-responsive-open page-with-super-sidebar">
<aside class="js-super-sidebar super-sidebar super-sidebar-loading" data-command-palette="{&quot;project_files_url&quot;:&quot;/manticoresearch/dev/-/files/master?format=json&quot;,&quot;project_blob_url&quot;:&quot;/manticoresearch/dev/-/blob/master&quot;}" data-force-desktop-expanded-sidebar="" data-root-path="/" data-sidebar="{&quot;is_logged_in&quot;:true,&quot;current_menu_items&quot;:[{&quot;id&quot;:&quot;project_overview&quot;,&quot;title&quot;:&quot;Project overview&quot;,&quot;icon&quot;:&quot;project&quot;,&quot;link&quot;:&quot;/manticoresearch/dev&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-project rspec-project-link&quot;,&quot;is_active&quot;:false},{&quot;title&quot;:&quot;Manage&quot;,&quot;icon&quot;:&quot;users&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/activity&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;activity&quot;,&quot;title&quot;:&quot;Activity&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/activity&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-project-activity&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;members&quot;,&quot;title&quot;:&quot;Members&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/project_members&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;labels&quot;,&quot;title&quot;:&quot;Labels&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/labels&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Plan&quot;,&quot;icon&quot;:&quot;planning&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/issues&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;project_issue_list&quot;,&quot;title&quot;:&quot;Issues&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/issues&quot;,&quot;pill_count&quot;:&quot;754&quot;,&quot;link_classes&quot;:&quot;shortcuts-issues has-sub-items&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;boards&quot;,&quot;title&quot;:&quot;Issue boards&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/boards&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-issue-boards&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;milestones&quot;,&quot;title&quot;:&quot;Milestones&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/milestones&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;project_wiki&quot;,&quot;title&quot;:&quot;Wiki&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/wikis/home&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-wiki&quot;,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Code&quot;,&quot;icon&quot;:&quot;code&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/merge_requests&quot;,&quot;is_active&quot;:true,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;project_merge_request_list&quot;,&quot;title&quot;:&quot;Merge requests&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/merge_requests&quot;,&quot;pill_count&quot;:&quot;3&quot;,&quot;link_classes&quot;:&quot;shortcuts-merge_requests&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;files&quot;,&quot;title&quot;:&quot;Repository&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/tree/master&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-tree&quot;,&quot;is_active&quot;:true},{&quot;id&quot;:&quot;branches&quot;,&quot;title&quot;:&quot;Branches&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/branches&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;commits&quot;,&quot;title&quot;:&quot;Commits&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/commits/master?ref_type=heads&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-commits&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;tags&quot;,&quot;title&quot;:&quot;Tags&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/tags&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;graphs&quot;,&quot;title&quot;:&quot;Repository graph&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/network/master?ref_type=heads&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-network&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;compare&quot;,&quot;title&quot;:&quot;Compare revisions&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/compare?from=master\u0026to=master&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;project_snippets&quot;,&quot;title&quot;:&quot;Snippets&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/snippets&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-snippets&quot;,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Build&quot;,&quot;icon&quot;:&quot;rocket&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/pipelines&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;pipelines&quot;,&quot;title&quot;:&quot;Pipelines&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/pipelines&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-pipelines&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;jobs&quot;,&quot;title&quot;:&quot;Jobs&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/jobs&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-builds&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;pipelines_editor&quot;,&quot;title&quot;:&quot;Pipeline editor&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/ci/editor?branch_name=master&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;pipeline_schedules&quot;,&quot;title&quot;:&quot;Pipeline schedules&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/pipeline_schedules&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-builds&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;artifacts&quot;,&quot;title&quot;:&quot;Artifacts&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/artifacts&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-builds&quot;,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Secure&quot;,&quot;icon&quot;:&quot;shield&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/audit_events&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;audit_events&quot;,&quot;title&quot;:&quot;Audit events&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/audit_events&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;configuration&quot;,&quot;title&quot;:&quot;Security configuration&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/security/configuration&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Deploy&quot;,&quot;icon&quot;:&quot;deployments&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/releases&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;releases&quot;,&quot;title&quot;:&quot;Releases&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/releases&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-deployments-releases&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;feature_flags&quot;,&quot;title&quot;:&quot;Feature flags&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/feature_flags&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-feature-flags&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;container_registry&quot;,&quot;title&quot;:&quot;Container Registry&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/container_registry&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;pages&quot;,&quot;title&quot;:&quot;Pages&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/pages&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Operate&quot;,&quot;icon&quot;:&quot;cloud-pod&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/environments&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;environments&quot;,&quot;title&quot;:&quot;Environments&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/environments&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-environments&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;kubernetes&quot;,&quot;title&quot;:&quot;Kubernetes clusters&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/clusters&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-kubernetes&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;terraform_states&quot;,&quot;title&quot;:&quot;Terraform states&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/terraform&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;google_cloud&quot;,&quot;title&quot;:&quot;Google Cloud&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/google_cloud/configuration&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Monitor&quot;,&quot;icon&quot;:&quot;monitor&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/error_tracking&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;error_tracking&quot;,&quot;title&quot;:&quot;Error Tracking&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/error_tracking&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;alert_management&quot;,&quot;title&quot;:&quot;Alerts&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/alert_management&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;incidents&quot;,&quot;title&quot;:&quot;Incidents&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/incidents&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;service_desk&quot;,&quot;title&quot;:&quot;Service Desk&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/issues/service_desk&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Analyze&quot;,&quot;icon&quot;:&quot;chart&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/-/value_stream_analytics&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;cycle_analytics&quot;,&quot;title&quot;:&quot;Value stream analytics&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/value_stream_analytics&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-project-cycle-analytics&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;contributors&quot;,&quot;title&quot;:&quot;Contributor statistics&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/graphs/master?ref_type=heads&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;ci_cd_analytics&quot;,&quot;title&quot;:&quot;CI/CD analytics&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/pipelines/charts&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;repository_analytics&quot;,&quot;title&quot;:&quot;Repository analytics&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/graphs/master/charts&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:&quot;shortcuts-repository-charts&quot;,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;model_experiments&quot;,&quot;title&quot;:&quot;Model experiments&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/ml/experiments&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false}],&quot;separated&quot;:false},{&quot;title&quot;:&quot;Settings&quot;,&quot;icon&quot;:&quot;settings&quot;,&quot;link&quot;:&quot;/manticoresearch/dev/edit&quot;,&quot;is_active&quot;:false,&quot;pill_count&quot;:null,&quot;items&quot;:[{&quot;id&quot;:&quot;general&quot;,&quot;title&quot;:&quot;General&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/edit&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;integrations&quot;,&quot;title&quot;:&quot;Integrations&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/settings/integrations&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;webhooks&quot;,&quot;title&quot;:&quot;Webhooks&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/hooks&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;access_tokens&quot;,&quot;title&quot;:&quot;Access Tokens&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/settings/access_tokens&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;repository&quot;,&quot;title&quot;:&quot;Repository&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/settings/repository&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;merge_request_settings&quot;,&quot;title&quot;:&quot;Merge requests&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/settings/merge_requests&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;ci_cd&quot;,&quot;title&quot;:&quot;CI/CD&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/settings/ci_cd&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;packages_and_registries&quot;,&quot;title&quot;:&quot;Packages and registries&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/settings/packages_and_registries&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;monitor&quot;,&quot;title&quot;:&quot;Monitor&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/settings/operations&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false},{&quot;id&quot;:&quot;usage_quotas&quot;,&quot;title&quot;:&quot;Usage Quotas&quot;,&quot;icon&quot;:null,&quot;link&quot;:&quot;/manticoresearch/dev/-/usage_quotas&quot;,&quot;pill_count&quot;:null,&quot;link_classes&quot;:null,&quot;is_active&quot;:false}],&quot;separated&quot;:true}],&quot;current_context_header&quot;:{&quot;title&quot;:&quot;Manticore Search&quot;,&quot;avatar&quot;:&quot;/uploads/-/system/project/avatar/3858465/download.jpg&quot;,&quot;id&quot;:3858465},&quot;support_path&quot;:&quot;https://about.gitlab.com/get-help/&quot;,&quot;display_whats_new&quot;:true,&quot;whats_new_most_recent_release_items_count&quot;:5,&quot;whats_new_version_digest&quot;:&quot;ca111af52506d69a6f80f5dc78081a21817623e4e2c16f3a7b1e97cc026708b6&quot;,&quot;show_version_check&quot;:false,&quot;gitlab_version&quot;:{&quot;major&quot;:16,&quot;minor&quot;:3,&quot;patch&quot;:0,&quot;suffix_s&quot;:&quot;&quot;},&quot;gitlab_version_check&quot;:{&quot;latest_stable_versions&quot;:[],&quot;latest_version&quot;:&quot;16.2.1&quot;,&quot;severity&quot;:&quot;success&quot;,&quot;critical_vulnerability&quot;:false,&quot;details&quot;:&quot;&quot;},&quot;search&quot;:{&quot;search_path&quot;:&quot;/search&quot;,&quot;issues_path&quot;:&quot;/dashboard/issues&quot;,&quot;mr_path&quot;:&quot;/dashboard/merge_requests&quot;,&quot;autocomplete_path&quot;:&quot;/search/autocomplete&quot;,&quot;search_context&quot;:{&quot;group&quot;:{&quot;id&quot;:1843808,&quot;name&quot;:&quot;manticoresearch&quot;,&quot;full_name&quot;:&quot;manticoresearch&quot;},&quot;group_metadata&quot;:{&quot;issues_path&quot;:&quot;/groups/manticoresearch/-/issues&quot;,&quot;mr_path&quot;:&quot;/groups/manticoresearch/-/merge_requests&quot;},&quot;project&quot;:{&quot;id&quot;:3858465,&quot;name&quot;:&quot;Manticore Search&quot;},&quot;project_metadata&quot;:{&quot;mr_path&quot;:&quot;/manticoresearch/dev/-/merge_requests&quot;,&quot;issues_path&quot;:&quot;/manticoresearch/dev/-/issues&quot;},&quot;code_search&quot;:true,&quot;ref&quot;:&quot;master&quot;,&quot;scope&quot;:null,&quot;for_snippets&quot;:null}},&quot;panel_type&quot;:&quot;project&quot;,&quot;name&quot;:&quot;manticore_bot&quot;,&quot;username&quot;:&quot;manticore_bot&quot;,&quot;avatar_url&quot;:&quot;https://secure.gravatar.com/avatar/e5d8eaba32898b9b901210d0d5a8c383?s=80\u0026d=identicon&quot;,&quot;has_link_to_profile&quot;:true,&quot;link_to_profile&quot;:&quot;https://gitlab.com/manticore_bot&quot;,&quot;logo_url&quot;:null,&quot;status&quot;:{&quot;can_update&quot;:true,&quot;busy&quot;:null,&quot;customized&quot;:null,&quot;availability&quot;:&quot;&quot;,&quot;emoji&quot;:null,&quot;message_html&quot;:null,&quot;message&quot;:null,&quot;clear_after&quot;:null},&quot;settings&quot;:{&quot;has_settings&quot;:true,&quot;profile_path&quot;:&quot;/-/profile&quot;,&quot;profile_preferences_path&quot;:&quot;/-/profile/preferences&quot;},&quot;user_counts&quot;:{&quot;assigned_issues&quot;:0,&quot;assigned_merge_requests&quot;:0,&quot;review_requested_merge_requests&quot;:0,&quot;todos&quot;:0,&quot;last_update&quot;:1690655310846},&quot;can_sign_out&quot;:true,&quot;sign_out_link&quot;:&quot;/users/sign_out&quot;,&quot;issues_dashboard_path&quot;:&quot;/dashboard/issues?assignee_username=manticore_bot&quot;,&quot;todos_dashboard_path&quot;:&quot;/dashboard/todos&quot;,&quot;create_new_menu_groups&quot;:[{&quot;name&quot;:&quot;In this project&quot;,&quot;items&quot;:[{&quot;text&quot;:&quot;New issue&quot;,&quot;href&quot;:&quot;/manticoresearch/dev/-/issues/new&quot;,&quot;component&quot;:null,&quot;extraAttrs&quot;:{&quot;data-track-label&quot;:&quot;new_issue&quot;,&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-property&quot;:&quot;nav_create_menu&quot;,&quot;data-testid&quot;:&quot;create_menu_item&quot;,&quot;data-qa-create-menu-item&quot;:&quot;new_issue&quot;}},{&quot;text&quot;:&quot;New merge request&quot;,&quot;href&quot;:&quot;/manticoresearch/dev/-/merge_requests/new&quot;,&quot;component&quot;:null,&quot;extraAttrs&quot;:{&quot;data-track-label&quot;:&quot;new_mr&quot;,&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-property&quot;:&quot;nav_create_menu&quot;,&quot;data-testid&quot;:&quot;create_menu_item&quot;,&quot;data-qa-create-menu-item&quot;:&quot;new_mr&quot;}},{&quot;text&quot;:&quot;New snippet&quot;,&quot;href&quot;:&quot;/manticoresearch/dev/-/snippets/new&quot;,&quot;component&quot;:null,&quot;extraAttrs&quot;:{&quot;data-track-label&quot;:&quot;new_snippet&quot;,&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-property&quot;:&quot;nav_create_menu&quot;,&quot;data-testid&quot;:&quot;create_menu_item&quot;,&quot;data-qa-create-menu-item&quot;:&quot;new_snippet&quot;}},{&quot;text&quot;:&quot;Invite members&quot;,&quot;href&quot;:null,&quot;component&quot;:&quot;invite_members&quot;,&quot;extraAttrs&quot;:{&quot;data-track-label&quot;:&quot;invite&quot;,&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-property&quot;:&quot;nav_create_menu&quot;,&quot;data-testid&quot;:&quot;create_menu_item&quot;,&quot;data-qa-create-menu-item&quot;:&quot;invite&quot;}}]},{&quot;name&quot;:&quot;In GitLab&quot;,&quot;items&quot;:[{&quot;text&quot;:&quot;New project/repository&quot;,&quot;href&quot;:&quot;/projects/new&quot;,&quot;component&quot;:null,&quot;extraAttrs&quot;:{&quot;data-track-label&quot;:&quot;general_new_project&quot;,&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-property&quot;:&quot;nav_create_menu&quot;,&quot;data-testid&quot;:&quot;create_menu_item&quot;,&quot;data-qa-create-menu-item&quot;:&quot;general_new_project&quot;}},{&quot;text&quot;:&quot;New group&quot;,&quot;href&quot;:&quot;/groups/new&quot;,&quot;component&quot;:null,&quot;extraAttrs&quot;:{&quot;data-track-label&quot;:&quot;general_new_group&quot;,&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-property&quot;:&quot;nav_create_menu&quot;,&quot;data-testid&quot;:&quot;create_menu_item&quot;,&quot;data-qa-create-menu-item&quot;:&quot;general_new_group&quot;}},{&quot;text&quot;:&quot;New snippet&quot;,&quot;href&quot;:&quot;/-/snippets/new&quot;,&quot;component&quot;:null,&quot;extraAttrs&quot;:{&quot;data-track-label&quot;:&quot;general_new_snippet&quot;,&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-property&quot;:&quot;nav_create_menu&quot;,&quot;data-testid&quot;:&quot;create_menu_item&quot;,&quot;data-qa-create-menu-item&quot;:&quot;general_new_snippet&quot;}}]}],&quot;merge_request_menu&quot;:[{&quot;name&quot;:&quot;Merge requests&quot;,&quot;items&quot;:[{&quot;text&quot;:&quot;Assigned&quot;,&quot;href&quot;:&quot;/dashboard/merge_requests?assignee_username=manticore_bot&quot;,&quot;count&quot;:0,&quot;userCount&quot;:&quot;assigned_merge_requests&quot;,&quot;extraAttrs&quot;:{&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-label&quot;:&quot;merge_requests_assigned&quot;,&quot;data-track-property&quot;:&quot;nav_core_menu&quot;,&quot;class&quot;:&quot;dashboard-shortcuts-merge_requests&quot;}},{&quot;text&quot;:&quot;Review requests&quot;,&quot;href&quot;:&quot;/dashboard/merge_requests?reviewer_username=manticore_bot&quot;,&quot;count&quot;:0,&quot;userCount&quot;:&quot;review_requested_merge_requests&quot;,&quot;extraAttrs&quot;:{&quot;data-track-action&quot;:&quot;click_link&quot;,&quot;data-track-label&quot;:&quot;merge_requests_to_review&quot;,&quot;data-track-property&quot;:&quot;nav_core_menu&quot;,&quot;class&quot;:&quot;dashboard-shortcuts-review_requests&quot;}}]}],&quot;projects_path&quot;:&quot;/dashboard/projects&quot;,&quot;groups_path&quot;:&quot;/dashboard/groups&quot;,&quot;gitlab_com_but_not_canary&quot;:true,&quot;gitlab_com_and_canary&quot;:null,&quot;canary_toggle_com_url&quot;:&quot;https://next.gitlab.com&quot;,&quot;current_context&quot;:{&quot;namespace&quot;:&quot;projects&quot;,&quot;item&quot;:{&quot;id&quot;:3858465,&quot;name&quot;:&quot;Manticore Search&quot;,&quot;namespace&quot;:&quot;manticoresearch / Manticore Search&quot;,&quot;webUrl&quot;:&quot;/manticoresearch/dev&quot;,&quot;avatarUrl&quot;:&quot;/uploads/-/system/project/avatar/3858465/download.jpg&quot;}},&quot;context_switcher_links&quot;:[{&quot;title&quot;:&quot;Your work&quot;,&quot;link&quot;:&quot;/&quot;,&quot;icon&quot;:&quot;work&quot;},{&quot;title&quot;:&quot;Explore&quot;,&quot;link&quot;:&quot;/explore&quot;,&quot;icon&quot;:&quot;compass&quot;}],&quot;pinned_items&quot;:[&quot;project_issue_list&quot;,&quot;project_merge_request_list&quot;],&quot;update_pins_url&quot;:&quot;https://gitlab.com/-/users/pins&quot;,&quot;is_impersonating&quot;:false,&quot;stop_impersonation_path&quot;:&quot;/admin/impersonation&quot;,&quot;shortcut_links&quot;:[{&quot;title&quot;:&quot;Milestones&quot;,&quot;href&quot;:&quot;/dashboard/milestones&quot;,&quot;css_class&quot;:&quot;dashboard-shortcuts-milestones&quot;},{&quot;title&quot;:&quot;Snippets&quot;,&quot;href&quot;:&quot;/dashboard/snippets&quot;,&quot;css_class&quot;:&quot;dashboard-shortcuts-snippets&quot;},{&quot;title&quot;:&quot;Activity&quot;,&quot;href&quot;:&quot;/dashboard/activity&quot;,&quot;css_class&quot;:&quot;dashboard-shortcuts-activity&quot;},{&quot;title&quot;:&quot;Create a new issue&quot;,&quot;href&quot;:&quot;/manticoresearch/dev/-/issues/new&quot;,&quot;css_class&quot;:&quot;shortcuts-new-issue&quot;}],&quot;show_tanuki_bot&quot;:false,&quot;trial&quot;:{&quot;has_start_trial&quot;:false,&quot;url&quot;:&quot;/-/trials/new?glm_content=top-right-dropdown\u0026glm_source=gitlab.com&quot;}}" data-toggle-new-nav-endpoint="https://gitlab.com/-/profile/preferences"></aside>
<div data-version-digest="ca111af52506d69a6f80f5dc78081a21817623e4e2c16f3a7b1e97cc026708b6" id="whats-new-app"></div>

<div class="content-wrapper">
<div class="mobile-overlay"></div>

<div class="alert-wrapper gl-force-block-formatting-context">






























<div class="top-bar-fixed container-fluid" data-testid="top-bar">
<div class="top-bar-container gl-display-flex gl-align-items-center gl-gap-2">
<button class="gl-button btn btn-icon btn-md btn-default btn-default-tertiary js-super-sidebar-toggle-expand super-sidebar-toggle gl-ml-n3" title="Expand sidebar" aria-controls="super-sidebar" aria-expanded="false" aria-label="Navigation sidebar" type="button"><svg class="s16 gl-icon gl-button-icon " data-testid="sidebar-icon"><use href="/assets/icons-5af6a635d810e1104f2def09ede3ada64866640a56f75b704457f18be086e881.svg#sidebar"></use></svg>

</button>
<nav aria-label="Breadcrumbs" class="breadcrumbs" data-qa-selector="breadcrumb_links_content" data-testid="breadcrumb-links">
<ul class="list-unstyled breadcrumbs-list js-breadcrumbs-list">
<li><a class="group-path breadcrumb-item-text js-breadcrumb-item-text " href="/manticoresearch"><img alt="manticoresearch" class="avatar-tile lazy" width="15" height="15" data-src="/uploads/-/system/group/avatar/1843808/manticore-logo-central.png" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" />manticoresearch</a><svg class="s8 breadcrumbs-list-angle" data-testid="chevron-lg-right-icon"><use href="/assets/icons-5af6a635d810e1104f2def09ede3ada64866640a56f75b704457f18be086e881.svg#chevron-lg-right"></use></svg></li> <li><a href="/manticoresearch/dev"><img alt="Manticore Search" class="avatar-tile lazy" width="15" height="15" data-src="/uploads/-/system/project/avatar/3858465/download.jpg" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" /><span class="breadcrumb-item-text js-breadcrumb-item-text">Manticore Search</span></a><svg class="s8 breadcrumbs-list-angle" data-testid="chevron-lg-right-icon"><use href="/assets/icons-5af6a635d810e1104f2def09ede3ada64866640a56f75b704457f18be086e881.svg#chevron-lg-right"></use></svg></li>

<li data-qa-selector="breadcrumb_current_link" data-testid="breadcrumb-current-link">
<a href="/manticoresearch/dev/-/blob/master/src/searchdsql.cpp">Repository</a>
</li>
</ul>
<script type="application/ld+json">
{"@context":"https://schema.org","@type":"BreadcrumbList","itemListElement":[{"@type":"ListItem","position":1,"name":"manticoresearch","item":"https://gitlab.com/manticoresearch"},{"@type":"ListItem","position":2,"name":"Manticore Search","item":"https://gitlab.com/manticoresearch/dev"},{"@type":"ListItem","position":3,"name":"Repository","item":"https://gitlab.com/manticoresearch/dev/-/blob/master/src/searchdsql.cpp"}]}

</script>
</nav>



</div>
</div>

</div>
<div class="container-fluid container-limited project-highlight-puc">
<main class="content" id="content-body" itemscope itemtype="http://schema.org/SoftwareSourceCode">
<div class="flash-container flash-container-page sticky" data-qa-selector="flash_container">
</div>


<div class="js-invite-members-modal" data-access-levels="{&quot;Guest&quot;:10,&quot;Reporter&quot;:20,&quot;Developer&quot;:30,&quot;Maintainer&quot;:40}" data-default-access-level="10" data-full-path="manticoresearch/dev" data-help-link="https://gitlab.com/help/user/permissions" data-id="3858465" data-is-project="true" data-name="Manticore Search" data-reload-page-on-submit="false" data-root-id="1843808"></div>


<div class="js-signature-container" data-signatures-path="/manticoresearch/dev/-/commits/c33ddbd5b2fc8317cab8588fc747f915a6e776e6/signatures?limit=1"></div>

<div class="tree-holder gl-pt-4" id="tree-holder">
<div class="nav-block">
<div class="tree-ref-container">
<div class="tree-ref-holder gl-max-w-26">
<div data-project-id="3858465" data-project-root-path="/manticoresearch/dev" data-ref="master" data-ref-type="" id="js-tree-ref-switcher"></div>
</div>
<ul class="breadcrumb repo-breadcrumb">
<li class="breadcrumb-item">
<a href="/manticoresearch/dev/-/tree/master">dev
</a></li>
<li class="breadcrumb-item">
<a href="/manticoresearch/dev/-/tree/master/src">src</a>
</li>
<li class="breadcrumb-item">
<a href="/manticoresearch/dev/-/blob/master/src/searchdsql.cpp"><strong>searchdsql.cpp</strong>
</a></li>
</ul>
</div>
<div class="tree-controls gl-children-ml-sm-3"><a class="gl-button btn btn-md btn-default shortcuts-find-file" rel="nofollow" href="/manticoresearch/dev/-/find_file/master"><span class="gl-button-text">
Find file

</span>

</a><a class="gl-button btn btn-md btn-default js-blob-blame-link" href="/manticoresearch/dev/-/blame/master/src/searchdsql.cpp"><span class="gl-button-text">
Blame
</span>

</a><a class="gl-button btn btn-md btn-default " href="/manticoresearch/dev/-/commits/master/src/searchdsql.cpp"><span class="gl-button-text">
History
</span>

</a><a class="gl-button btn btn-md btn-default js-data-file-blob-permalink-url" href="/manticoresearch/dev/-/blob/8149f5d747f9b70553acc74dfce54ad11fc37625/src/searchdsql.cpp"><span class="gl-button-text">
Permalink
</span>

</a></div>
</div>

<div class="info-well d-none d-sm-block">
<div class="well-segment">
<ul class="blob-commit-info">
<li class="commit flex-row js-toggle-container" id="commit-c33ddbd5">
<div class="avatar-cell d-none d-sm-block">
<a href="/klirichek"><img alt="Aleksey N. Vinogradov&#39;s avatar" src="https://secure.gravatar.com/avatar/14592f8c1d949e382f6235a6129e3c5e?s=80&amp;d=identicon" class="avatar s40 d-none d-sm-inline-block" title="Aleksey N. Vinogradov"></a>
</div>
<div class="commit-detail flex-list gl-display-flex gl-justify-content-space-between gl-align-items-center gl-flex-grow-1 gl-min-w-0">
<div class="commit-content" data-qa-selector="commit_content">
<a class="commit-row-message item-title js-onboarding-commit-item " href="/manticoresearch/dev/-/commit/c33ddbd5b2fc8317cab8588fc747f915a6e776e6">add switchover support to syntax</a>
<span class="commit-row-message d-inline d-sm-none">
&middot;
c33ddbd5
</span>
<div class="committer">
<a class="commit-author-link js-user-link" data-user-id="1515333" href="/klirichek">Aleksey N. Vinogradov</a> authored <time class="js-timeago" title="Jun 17, 2023 9:21am" datetime="2023-06-17T09:21:19Z" data-toggle="tooltip" data-placement="bottom" data-container="body">Jun 17, 2023</time>
</div>

</div>
<div class="commit-actions flex-row">

<div class="js-commit-pipeline-status" data-endpoint="/manticoresearch/dev/-/commit/c33ddbd5b2fc8317cab8588fc747f915a6e776e6/pipelines?ref=master"></div>
<div class="commit-sha-group btn-group d-none d-sm-flex">
<div class="label label-monospace monospace">
c33ddbd5
</div>
<button class="btn gl-button btn btn-default btn-icon" data-toggle="tooltip" data-placement="bottom" data-container="body" data-clipboard-text="c33ddbd5b2fc8317cab8588fc747f915a6e776e6" type="button" title="Copy commit SHA" aria-label="Copy commit SHA" aria-live="polite"><svg class="s16 gl-icon" data-testid="copy-to-clipboard-icon"><use href="/assets/icons-5af6a635d810e1104f2def09ede3ada64866640a56f75b704457f18be086e881.svg#copy-to-clipboard"></use></svg></button>

</div>
</div>
</div>
</li>

</ul>
</div>

</div>
<div class="blob-content-holder js-per-page" data-blame-per-page="1000" id="blob-content-holder">
<div data-blob-path="src/searchdsql.cpp" data-explain-code-available="false" data-new-workspace-path="/-/remote_development/workspaces/new" data-original-branch="master" data-project-path="manticoresearch/dev" data-ref-type="" data-resource-id="gid://gitlab/Project/3858465" data-target-branch="master" data-user-id="gid://gitlab/User/2801001" id="js-view-blob-app">
<div class="gl-spinner-container" role="status"><span aria-label="Loading" class="gl-spinner gl-spinner-md gl-spinner-dark gl-vertical-align-text-bottom!"></span></div>
</div>
</div>

</div>

<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
  window.gl = window.gl || {};
  window.gl.webIDEPath = '/-/ide/project/manticoresearch/dev/edit/master/-/src/searchdsql.cpp'


//]]>
</script>

</main>
</div>


</div>
</div>



<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
if ('loading' in HTMLImageElement.prototype) {
  document.querySelectorAll('img.lazy').forEach(img => {
    img.loading = 'lazy';
    let imgUrl = img.dataset.src;
    // Only adding width + height for avatars for now
    if (imgUrl.indexOf('/avatar/') > -1 && imgUrl.indexOf('?') === -1) {
      const targetWidth = img.getAttribute('width') || img.width;
      imgUrl += `?width=${targetWidth}`;
    }
    img.src = imgUrl;
    img.removeAttribute('data-src');
    img.classList.remove('lazy');
    img.classList.add('js-lazy-loaded');
    img.dataset.testid = 'js_lazy_loaded_content';
  });
}

//]]>
</script>
<script nonce="B6eizRq8nHhnUsADWqyHRQ==">
//<![CDATA[
gl = window.gl || {};
gl.experiments = {};


//]]>
</script>

</body>
</html>

