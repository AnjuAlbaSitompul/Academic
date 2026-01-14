<script>
    $(function() {
        const perPage = 6;
        let page = 1,
            headers = [],
            rows = [];

        $.get("{{ asset('assets/data/data-siswa.csv') }}", function(csv) {
            const lines = csv.trim().split(/\r?\n/);
            headers = lines.shift().split(",");
            rows = lines.map(r => r.split(","));
            draw();
        });

        function draw() {
            $("#data-table thead").html(`
            <tr>
                <th>No</th>
                ${headers.map(h => `<th>${h.replace(/_/g,' ').toUpperCase()}</th>`).join("")}
                <th>Aksi</th>
            </tr>
        `);

            const start = (page - 1) * perPage;
            const slice = rows.slice(start, start + perPage);

            $("#data-table tbody").html(
                slice.map((r, i) => `
                <tr>
                    <td>${start + i + 1}</td>
                    ${r.map(c => `<td>${c}</td>`).join("")}
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning me-1"
                            data-bs-toggle="tooltip" title="Update Data">✏️</button>
                        <button class="btn btn-sm btn-danger"
                            data-bs-toggle="tooltip" title="Hapus Data">🗑️</button>
                    </td>
                </tr>
            `).join("")
            );

            $("#page-info").text(`Halaman ${page} dari ${Math.ceil(rows.length / perPage)}`);
            $('[data-bs-toggle="tooltip"]').tooltip();
        }

        $("#prev").on("click", () => page > 1 && (page--, draw()));
        $("#next").on("click", () => page < Math.ceil(rows.length / perPage) && (page++, draw()));
    });
</script>
