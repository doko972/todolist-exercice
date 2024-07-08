async function callAPIIncrease(params) {
    try {
        const response = await fetch("api.php?" + params);
        const json = await response.json();
        document.querySelector("[delete-id='" + json.task_id + "']").innerText = json.task_id;
    }
    catch(error) {
        console.error("Unable to load todolist datas from the server : " + error);
    }
}
