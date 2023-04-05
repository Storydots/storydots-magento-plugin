function updateGreeting(inputInstance) {
  require(["jquery", "mage/storage"], function ($, storage) {
    const currentCheckbox = $(inputInstance);

    //TODO GET CURRENT VALUE
    // currentCheckbox.prop("checked", currentValue);

    storage
      .put(
        "rest/V1/storydots/quote",
        JSON.stringify({ virtualGreeting: currentCheckbox.prop("checked") }),
        true,
        "application/json"
      )
      .done(function (res) {
        const checkboxes = document.querySelectorAll(
          "[name=virtual_greeting_checkbox]"
        );
        checkboxes.forEach((checkbox) => {
          checkbox.checked = currentCheckbox.prop("checked");
        });
        console.log("Order data updated. >> ", JSON.parse(res));
      });
  });
}

function syncGreetingState() {
  require(["jquery", "mage/storage"], function ($, storage) {
    storage
      .get("/rest/V1/storydots/quote")
      .done(function (res) {
        const json = JSON.parse(res);
        console.log(json);
        const checkboxes = document.querySelectorAll(
          "[name=virtual_greeting_checkbox]"
        );
        checkboxes.forEach((checkbox) => {
          checkbox.checked = Boolean(Number(json.value));
        });
      })
      .catch(console.log);
  });
}
window.document.onload = () => {
  syncGreetingState();
};
syncGreetingState();
const storydots = {
  updateGreeting,
  syncGreetingState,
};
