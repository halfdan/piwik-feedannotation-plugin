{include file="CoreAdminHome/templates/header.tpl"}
<h2>{'FeedAnnotation_Manage'|translate}</h2>

<section>
{include file="CoreHome/templates/sites_selection.tpl"
    idSite=$idSiteSelected sites=$idSitesAvailable showAllSitesItem=false
    showSelectedSite=true siteSelectorId="feedAnnotationSiteSelect"
    switchSiteOnSelect=true}
</section>

{include file="CoreAdminHome/templates/footer.tpl"}