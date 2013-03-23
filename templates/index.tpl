{include file="CoreAdminHome/templates/header.tpl"}
<script type="text/javascript" src="plugins/FeedAnnotation/templates/feedannotation.js"></script>

<h2>{'FeedAnnotation_Manage'|translate}</h2>

<p>{'FeedAnnotation_AdminDescription'|translate}</p>

<section class="sites_selector_container">
    <span style="line-height: 30px">{'FeedAnnotation_Website'|translate}:</span>
    {include file="CoreHome/templates/sites_selection.tpl"
        idSite=$idSiteSelected sites=$idSitesAvailable showAllSitesItem=false
        showSelectedSite=true siteSelectorId="feedAnnotationSiteSelect"
        switchSiteOnSelect=true siteName=$siteName}
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
    {if !empty($feeds)}
    {foreach from=$feeds key=i item=feed}
        <tr>
            <td>{$feed.feed_url}</td>
            <td>{if $feed.last_processed}{$feed.last_processed|date_format}{else}{'FeedAnnotation_Never'|translate}{/if}</td>
        </tr>
    {/foreach}
    {else}
        <tr>
            <td colspan="2">{'FeedAnnotation_NoFeeds'|translate}</td>
        </tr>
    {/if}
    </tbody>
    </table>
</div>

<h3>{'FeedAnnotation_AddFeed'|translate}</h3>

<form action="">
    <label>{'FeedAnnotation_FeedUrl'|translate} <input type="text" id="feed_url" placeholder="Feed URL" /></label>
    {ajaxErrorDiv id=ajaxErrorFeedUrl}
    {ajaxLoadingDiv id=ajaxLoadingFeedUrl}
    <input type="hidden" value="{$idSiteSelected}" /><br />
    <input type="submit" value="Save" class="submit" />
</form>
{include file="CoreAdminHome/templates/footer.tpl"}
