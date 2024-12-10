let home = document.querySelector(".home");
let btn = home.querySelector(".home-btn");

btn.addEventListener("click", async (e) => {
  let value = e.target.closest(".home").querySelector("input").value;

  try {
    let response = await fetch("tasks/search.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({ value }),
    });

    if (response.ok) {
      let data = await response.json();

      home.insertAdjacentHTML("beforeend", `<p>${data.value}</p>`);
    }
  } catch (e) {}
});
