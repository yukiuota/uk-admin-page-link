/*
 * UK Admin Page Link - Admin Scripts
 * Version: 1.0.1
 */

function updateIconPreview(select) {
    var row = select.closest(".ukapl-row");
    var customInput = row.querySelector(".ukapl-icon-custom");
    var preview = row.querySelector(".ukapl-icon-preview");
    var val = select.value;
    if(val === "custom") {
        customInput.style.display = "inline-block";
        preview.className = "dashicons " + (customInput.value || "dashicons-admin-page") + " ukapl-icon-preview";
    } else {
        customInput.style.display = "none";
        preview.className = "dashicons " + val + " ukapl-icon-preview";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll(".ukapl-icon-select").forEach(function(select){
        select.addEventListener("change", function(){ updateIconPreview(this); });
        updateIconPreview(select);
    });
    
    document.querySelectorAll(".ukapl-icon-custom").forEach(function(input){
        input.addEventListener("input", function(){
            var row = input.closest(".ukapl-row");
            var preview = row.querySelector(".ukapl-icon-preview");
            preview.className = "dashicons " + (input.value || "dashicons-admin-page") + " ukapl-icon-preview";
        });
    });
    
    document.getElementById("ukapl-add-row").onclick = function() {
        var rows = document.querySelectorAll("#ukapl-rows .ukapl-row");
        var lastRow = rows[rows.length-1];
        var newRow = lastRow.cloneNode(true);
        // input/selectの値をリセット
        newRow.querySelectorAll("input, select").forEach(function(el){
            if(el.tagName === "SELECT") el.selectedIndex = 0;
            else el.value = "";
        });
        newRow.querySelector(".ukapl-icon-custom").style.display = "none";
        newRow.querySelector(".ukapl-icon-preview").className = "dashicons dashicons-admin-page ukapl-icon-preview";
        // 新しいselect/inputにもイベント付与
        newRow.querySelector(".ukapl-icon-select").addEventListener("change", function(){ updateIconPreview(this); });
        newRow.querySelector(".ukapl-icon-custom").addEventListener("input", function(){
            var row = this.closest(".ukapl-row");
            var preview = row.querySelector(".ukapl-icon-preview");
            preview.className = "dashicons " + (this.value || "dashicons-admin-page") + " ukapl-icon-preview";
        });
        // 区切り線も追加
        var hr = document.createElement("hr");
        hr.className = "ukapl-hr";
        document.getElementById("ukapl-rows").appendChild(newRow);
        document.getElementById("ukapl-rows").appendChild(hr);
    };
    
    // 削除ボタン
    document.addEventListener("click", function(e) {
        if(e.target.classList.contains("ukapl-remove-row")) {
            var row = e.target.closest(".ukapl-row");
            var hr = row.nextElementSibling;
            if(document.querySelectorAll("#ukapl-rows .ukapl-row").length > 1) {
                row.remove();
                if(hr && hr.classList.contains("ukapl-hr")) hr.remove();
            } else {
                // 最後の1行はクリアのみ
                row.querySelectorAll("input, select").forEach(function(el){
                    if(el.tagName === "SELECT") el.selectedIndex = 0;
                    else el.value = "";
                });
                row.querySelector(".ukapl-icon-custom").style.display = "none";
                row.querySelector(".ukapl-icon-preview").className = "dashicons dashicons-admin-page ukapl-icon-preview";
            }
        }
    });
});
