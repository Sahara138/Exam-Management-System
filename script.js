"use strict";

const get_search_value = (search) => {
    // return;
    const search_input = $("#search");
    console.log(search_input);

    let request = $.post(
        window.location.origin +
        "/_ADMSproject/dashboard/admin/ajax.php",
        {
            search: search.value,
            searchajax: "true",
        }
    );

    request.done((response) => {
        // let messages = JSON.parse(response);

        console.log(response);
        return;
    });
};

$(document).ready(function () {

    const search_input = $("#search");
    const user_data = $("#user_data");
    // console.log(search_input);

    $(search_input).on("keyup", function (e) {
        e.preventDefault();

        let search_value = e.target.value ? e.target.value : "";

        let request = $.post(
            window.location.origin +
            "/_ADMSproject/dashboard/admin/ajax.php",
            {
                search: search_value,
                searchajax: "true",
            }
        );


        request.done((response) => {
            let messages = JSON.parse(response);
            let format = "";

            // console.log(response);
            // console.log(messages);
            // return;

            if (messages.length && messages.length > 0) {
                format = `
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Type</th>
                    <th>Action</th>
                </tr>`

                messages.forEach(function (user) {
                    format += `<tr>
                        <td>${user.U_ID}</td>
                        <td>${user.U_NAME}</td>
                        <td>${user.U_EMAIL}</td>
                        <td>${user.U_PHONE}</td>
                        <td>${user.U_TYPE}</td>
                        <td>
                            <a href="./edit-user.php?id=${user.U_ID}">Edit</a>
                            <a href="./delete-user.php?id=${user.U_ID}">Delete</a>
                        </td>
                    </tr>`;
                });

            } else {

                format = `
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                    <tr>
                        <td colspan="6">There are no User</td>
                    </tr>
                `;
            }

            $(user_data).html(format);
        });
    });
});