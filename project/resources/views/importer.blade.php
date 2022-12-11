<!DOCTYPE html>
<html>
    <head>
        <title>Importer</title>
    </head>
    <body>

        <form action="{{ route('Importer.importData') }}" method="post" enctype="multipart/form-data">
            @csrf
              <input type="file" name="file" class="form-control" accept=".html" required="true">
            <button type="submit">Import work orders</button>
        </form>

        <table style='border: 1px black solid; table-layout: auto; width: 550; text-align: center;'>
            <tr>
                <td>
                    id
                </td>
                <td>
                    type
                </td>
                <td>
                    run_at
                </td>
                <td>
                    entries_processed
                </td>
                <td>
                    entries_created
                </td>
            </tr>
            @foreach ($Logs ?? [] as $Log)
            <tr>
                <td>
                   {{ $Log['id'] }}
                </td>
                <td>
                    {{ $Log['type'] ?? "?"}}
                </td>
                <td>
                    {{ $Log['run_at'] }}
                </td>
                <td>
                    {{ $Log['entries_processed'] }}
                </td>
                <td>
                    {{ $Log['entries_created'] }}
                </td>
            </tr>
            @endforeach
        </table>
    </body>
</html>