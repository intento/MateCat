/*
    Common events used in translation and revise page when Review Extended is active
 */

if (ReviewExtended.enabled()) {
    $(document).on('files:appended', function () {
        // if (config.isReview) {
            SegmentActions.mountTranslationIssues();
            ReviewExtended.getSegmentsIssues();
        // }
    });

    $( window ).on( 'segmentClosed', function ( e ) {
        SegmentActions.closeSegmentIssuePanel(UI.getSegmentId(e.segment));
    } );

    $( window ).on( 'segmentOpened', function ( e ) {
        var panelClosed = localStorage.getItem(ReviewExtended.localStoragePanelClosed) == 'true';
        if (config.isReview && !panelClosed) {
            SegmentActions.openIssuesPanel({sid:e.segment.absoluteId}, false)
        }
    } );

    $(document).on('translation:change', function(e, data) {
        if (data.sid === UI.getSegmentId(UI.currentSegment)) {
            UI.getSegmentVersionsIssues(data.sid, UI.getSegmentFileId(data.segment));
        }
    });
}