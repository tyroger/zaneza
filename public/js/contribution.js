/* contribution.js */

function toggleContributionParticipantDisplay(idContribution){
	//alert(idContribution);
	var idTr="tr-contributeur-"+idContribution;
	$("#"+idTr).toggleClass("d-table-row").toggleClass("d-none");
}