let currentPage = 1;
let rowsPerPage = 10;
let rowsToShow = $("table tr");
let totalRows = rowsToShow.length - 3;
let totalPages = Math.ceil(totalRows / rowsPerPage);

function updateTable() {
  totalRows = rowsToShow.length - 3;
  totalPages = Math.ceil(totalRows / rowsPerPage);
  let startIndex = (currentPage - 1) * rowsPerPage + 2;
  let endIndex = startIndex + rowsPerPage;

  if (currentPage === 1) {
    $("#prevPageButton").addClass("disabled");
  } else {
    $("#prevPageButton").removeClass("disabled");
  }

  if (currentPage === totalPages) {
    $("#nextPageButton").addClass("disabled");
  } else {
    $("#nextPageButton").removeClass("disabled");
  }

  $("table tr").hide();
  $("table tr:lt(2)").show();
  rowsToShow.slice(startIndex, endIndex).show();
  $("table tr:last").show();

  $("#currentPageInput").attr("max", totalPages);
  $("#currentPageInput").val(currentPage);
  $("#totalOfPages").text("of " + totalPages);
}

$(document).ready(function () {
  $("#prevPageButton").click(function () {
    if (currentPage > 1) {
      currentPage--;
      updateTable();
      $("html, body").animate({ scrollTop: 0 }, "fast");
    }
  });

  $("#nextPageButton").click(function () {
    if (currentPage < totalPages) {
      currentPage++;
      updateTable();
      $("html, body").animate({ scrollTop: 0 }, "fast");
    }
  });

  $("#currentPageInput").on("change", function () {
    let newPage = parseInt($(this).val());
    if (!isNaN(newPage) && newPage >= 1 && newPage <= totalPages) {
      currentPage = newPage;
      updateTable();
      $("html, body").animate({ scrollTop: 0 }, "fast");
    }
  });

  updateTable();

  $("#opt10").prop("checked", true);
});

// Refresh all contacts
$(".refreshIconButton").click(function () {
  event.preventDefault();

  $.ajax({
    url: $(this).data("href"),
    type: "POST",
    success: function (response) {
      if (response) {
        firstRowsToShow = $("table tr:eq(0)").add($("table tr:eq(1)"));
        lastRowToShow = $("table tr:last");
        $("table tr:gt(1):not(:last)").remove();
        $("table tr:last").before($(response));
        rowsToShow = $("table tr");

        currentPage = 1;
        updateTable();
      }
    },
  });
});

// Filter contacts status
$("#filterStatus input").click(function () {
  let selectedFilter = [];
  $("#filterStatus input:checked").each(function () {
    selectedFilter.push($(this).val());
  });

  let typeColumnIndex = $("table th")
    .filter(function () {
      return $(this).text().indexOf("STATUS") !== -1;
    })
    .index();

  rowsToShow = $("table tr:eq(0)").add($("table tr:eq(1)"));

  if (selectedFilter.length != 0) {
    selectedFilter.forEach((filter) => {
      $("table tr").each(function () {
        if (
          $(this)
            .find(`td:nth-child(${typeColumnIndex + 1})`)
            .text()
            .trim() === filter
        ) {
          rowsToShow = rowsToShow.add($(this));
        }
      });
    });
    rowsToShow = rowsToShow.add($("table tr:last"));
  } else {
    rowsToShow = rowsToShow.add($("table tr:gt(1)"));
  }

  currentPage = 1;
  updateTable();
});

// Filter contacts types
$("#filterType input").click(function () {
  let selectedFilter = [];
  $("#filterType input:checked").each(function () {
    selectedFilter.push($(this).val());
  });

  let typeColumnIndex = $("table th")
    .filter(function () {
      return $(this).text().indexOf("TYPE") !== -1;
    })
    .index();

  rowsToShow = $("table tr:eq(0)").add($("table tr:eq(1)"));

  if (selectedFilter.length != 0) {
    selectedFilter.forEach((filter) => {
      $("table tr").each(function () {
        if (
          $(this)
            .find(`td:nth-child(${typeColumnIndex + 1})`)
            .text()
            .trim() === filter
        ) {
          rowsToShow = rowsToShow.add($(this));
        }
      });
    });
    rowsToShow = rowsToShow.add($("table tr:last"));
  } else {
    rowsToShow = rowsToShow.add($("table tr:gt(1)"));
  }

  currentPage = 1;
  updateTable();
});

// Edit single contact
$(document).on("click", ".editButton", function () {
  window.location.href = $(this).data("href");
});

// Delete single contact
$(document).on("click", ".deleteButton", function (event) {
  event.preventDefault();

  let confirmation = confirm("Are you sure you want to delete this contact?");

  if (confirmation) {
    let rowToDelete = $(this).closest("tr");

    $.ajax({
      url: $(this).attr("data-href"),
      type: "GET",
      success: function (response) {
        if (response) {
          alert("The contact was deleted SUCCESFULLY.");
          rowsToShow = rowsToShow.not(rowToDelete);
          rowToDelete.remove();
          currentPage = 1;
          updateTable();
        } else {
          alert("ERROR!");
        }
      },
    });
  }
});

// Select all checkboxes
$(document).on("click", "#selectAll", function () {
  let isChecked = $(this).prop("checked");
  $("input[type=checkbox]").prop("checked", isChecked);
});

// Trash icon hover effect
$(document).on("mouseenter", ".fa-trash-o", function () {
  $(this).removeClass("fa-trash-o").addClass("fa-trash");
});
$(document).on("mouseleave", ".fa-trash", function () {
  $(this).removeClass("fa-trash").addClass("fa-trash-o");
});

// Add icon hover effect
$(document).on("mouseenter", ".fa-address-card-o", function () {
  $(this).removeClass("fa-address-card-o").addClass("fa-address-card");
});
$(document).on("mouseleave", ".fa-address-card", function () {
  $(this).removeClass("fa-address-card").addClass("fa-address-card-o");
});

$(".addContact").click(function () {
  window.location.href = $(this).data("href");
});

// Delete multiple contacts
$(".deleteMultipleButton").click(function (event) {
  event.preventDefault();

  let confirmation = confirm(
    "Are you sure you want to delete the selected contacts?"
  );

  if (confirmation) {
    let contactsIDs = [];
    $("input:checkbox[name='contacts[]']:checked").each(function () {
      contactsIDs.push($(this).val());
    });

    $.ajax({
      url: $(this).data("href"),
      type: "POST",
      data: { contacts: contactsIDs },
      success: function (response) {
        if (response) {
          alert("The selected contacts were deleted successfully.");
          contactsIDs.forEach((id) => {
            rowsToShow = rowsToShow.not(
              $("input:checkbox[value='" + id + "']").closest("tr")
            );
            $("input:checkbox[value='" + id + "']")
              .closest("tr")
              .remove();
            currentPage = 1;
            updateTable();
          });
        } else {
          alert("There was an error deleting the selected contacts.");
        }
      },
    });
  }
});

// Filter number of rows
$('input[name="dropdownOption"]').change(function () {
  rowsPerPage = parseInt($(this).val());
  totalPages = Math.ceil(totalRows / rowsPerPage);
  $("#currentPageInput").attr("max", totalPages);
  $("#totalOfPages").text("of " + totalPages);
  currentPage = 1;
  updateTable();
});

// Sort contacts by Enterprise row
$("#sortEnterprise").click(function () {
  let enterpriseColumnIndex = $("table th")
    .filter(function () {
      return $(this).text().indexOf("ENTERPRISE") !== -1;
    })
    .index();

  let rows = $("table tr:gt(1):not(:last)").get();

  if (
    $("#sortEnterprise").hasClass("fa-sort-up") ||
    $("#sortEnterprise").hasClass("fa-sort")
  ) {
    rows.sort(function (a, b) {
      let A = $(a)
        .children("td")
        .eq(enterpriseColumnIndex)
        .text()
        .toUpperCase()
        .trim()
        .charAt(0);
      let B = $(b)
        .children("td")
        .eq(enterpriseColumnIndex)
        .text()
        .toUpperCase()
        .trim()
        .charAt(0);

      if (A < B) return -1;
      if (A > B) return 1;

      return 0;
    });

    if ($("#sortEnterprise").hasClass("fa-sort")) {
      $("#sortEnterprise").removeClass("fa-sort").addClass("fa-sort-down");
    } else {
      $("#sortEnterprise").removeClass("fa-sort-up").addClass("fa-sort-down");
    }
  } else {
    rows.sort(function (a, b) {
      let A = $(a)
        .children("td")
        .eq(enterpriseColumnIndex)
        .text()
        .toUpperCase()
        .trim()
        .charAt(0);
      let B = $(b)
        .children("td")
        .eq(enterpriseColumnIndex)
        .text()
        .toUpperCase()
        .trim()
        .charAt(0);

      if (A > B) return -1;
      if (A < B) return 1;

      return 0;
    });

    $("#sortEnterprise").removeClass("fa-sort-down").addClass("fa-sort-up");
  }

  let rowsArrayToShow = $("table tr:eq(0)").add($("table tr:eq(1)")).toArray();
  rowsArrayToShow = rowsArrayToShow.concat(rows);
  rowsArrayToShow.push($("table tr:last").get(0));
  rowsToShow = $(rowsArrayToShow);

  $("table tr:gt(1):not(:last)").remove();
  $("table tr:last").before(rowsToShow);

  currentPage = 1;
  updateTable();
});

$("#searchBar").on("keyup", function () {
  var value = $(this).val().toLowerCase().trim();
  var matchingRows = $("table tr:gt(1):not(:last)").filter(function () {
    return $(this).text().toLowerCase().indexOf(value) > -1;
  });

  rowsToShow = $("table tr:eq(0)").add($("table tr:eq(1)"));
  rowsToShow = rowsToShow.add(matchingRows);
  rowsToShow = rowsToShow.add($("table tr:last"));
  currentPage = 1;
  updateTable();
});

// Color of the status
$("#status").change(function() {
  var selectedOption = $(this).val();
  console.log(selectedOption)
  switch(selectedOption) {
    case "WITHOUT STATE":
      $(this).css("background-color", "#ffffff");
      break;
    case "START CONTACT":
      $(this).css("background-color", "#b3e5fc");
      break;
    case "IN CONTACT":
      $(this).css("background-color", "#c8e6c9");
      break;
    case "WITHOUT CONTACT":
      $(this).css("background-color", "#bdbdbd");
      break;
    case "WAITING ANSWER":
      $(this).css("background-color", "#fff9c4");
      break;
    case "PENDING ANSWER":
      $(this).css("background-color", "#ffe0b2");
      break;
    case "GOOD RELATIONSHIP":
      $(this).css("background-color", "#a5d6a7");
      break;
    case "BAD RELATIONSHIP":
      $(this).css("background-color", "#ef9a9a");
      break;
    default:
      $(this).css("background-color", "white");
  }
});