{include file="CoreAdminHome/templates/header.tpl"}
<h2>{'FeedAnnotation_Manage'|translate}</h2>

<p>{'FeedAnnotation_AdminDescription'|translate}</p>

<section>
    {include file="CoreHome/templates/sites_selection.tpl"
        idSite=$idSiteSelected sites=$idSitesAvailable showAllSitesItem=false
        showSelectedSite=true siteSelectorId="feedAnnotationSiteSelect"
        switchSiteOnSelect=true}
</section>

<div class="entityContainer">
    <table class="entityTable dataTable" id="editSites">
    <thead>
        <tr>
            <th>{'FeedAnnotation_FeedUrl'|translate}</th>
            <th>{'FeedAnnotation_LastProcessed'|translate}</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    </table>
</div>

{include file="CoreAdminHome/templates/footer.tpl"}