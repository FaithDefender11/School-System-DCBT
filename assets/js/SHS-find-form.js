var shsEvaluation = document.getElementById("shsEvaluation");
var shsPayment = document.getElementById("shsPayment");
var shsApproval = document.getElementById("shsApproval");
var shsEnrolled = document.getElementById("shsEnrolled");

//Add event listeners
shsEvaluation.addEventListener("click", shsEval);
shsPayment.addEventListener("click", shsPay);
shsApproval.addEventListener("click", shsApprove);
shsEnrolled.addEventListener("click", shsEnroll);

function shsEval() {
    shsEvaluation.style.background = "var(--mainContentBG)";
    shsEvaluation.style.color = "black";
    shsPayment.style.background = "var(--theme)";
    shsPayment.style.color = "white";
    shsApproval.style.background = "var(--theme)";
    shsApproval.style.color = "white";
    shsEnrolled.style.background = "var(--theme)";
    shsEnrolled.style.color = "white";

    shsPayment.removeEventListener("click", shsPay);
    shsApproval.removeEventListener("click", shsApprove);
    shsEnrolled.removeEventListener("click", shsEnroll);
}

function shsPay() {
    shsEvaluation.style.background = "var(--theme)";
    shsEvaluation.style.color = "white";
    shsPayment.style.background = "var(--mainContentBG)";
    shsPayment.style.color = "black";
    shsApproval.style.background = "var(--theme)";
    shsApproval.style.color = "white";
    shsEnrolled.style.background = "var(--theme)";
    shsEnrolled.style.color = "white";

    shsEvaluation.removeEventListener("click", shsEval);
    shsApproval.removeEventListener("click", shsApprove);
    shsEnrolled.removeEventListener("click", shsEnroll);
}

function shsApprove() {
    shsEvaluation.style.background = "var(--theme)";
    shsEvaluation.style.color = "white";
    shsPayment.style.background = "var(--theme)";
    shsPayment.style.color = "white";
    shsApproval.style.background = "var(--mainContentBG)";
    shsApproval.style.color = "black";
    shsEnrolled.style.background = "var(--theme)";
    shsEnrolled.style.color = "white";

    shsEvaluation.removeEventListener("click", shsEval);
    shsPayment.removeEventListener("click", shsPay);
    shsEnrolled.removeEventListener("click", shsEnroll);
}

function shsEnroll() {
    shsEvaluation.style.background = "var(--theme)";
    shsEvaluation.style.color = "white";
    shsPayment.style.background = "var(--theme)";
    shsPayment.style.color = "white";
    shsApproval.style.background = "var(--theme)";
    shsApproval.style.color = "white";
    shsEnrolled.style.background = "var(--mainContentBG)";
    shsEnrolled.style.color = "black";

    shsEvaluation.removeEventListener("click", shsEval);
    shsPayment.removeEventListener("click", shsPay);
    shsApproval.removeEventListener("click", shsApprove);
}