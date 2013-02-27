{include file="CoreAdminHome/templates/header.tpl"}
<h2>{'FeedAnnotation_Manage'|translate}</h2>

{include file="CoreHome/templates/sites_selection.tpl"
idSite=$idSiteSelected sites=$idSitesAvailable showAllSitesItem=false  siteSelectorId="feedAnnotationSiteSelect" switchSiteOnSelect=true}

{include file="CoreAdminHome/templates/footer.tpl"}