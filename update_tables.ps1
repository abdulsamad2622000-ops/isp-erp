$files = @(
    'resources/views/customers/index.blade.php',
    'resources/views/areas/index.blade.php',
    'resources/views/packages/index.blade.php',
    'resources/views/connections/index.blade.php',
    'resources/views/invoices/index.blade.php',
    'resources/views/payments/index.blade.php',
    'resources/views/complaints/index.blade.php',
    'resources/views/inventory/index.blade.php',
    'resources/views/suspensions/index.blade.php',
    'resources/views/expenses/index.blade.php',
    'resources/views/notifications/index.blade.php',
    'resources/views/users/index.blade.php'
)
foreach ($file in $files) {
    $content = Get-Content $file -Raw
    $content = $content -replace '<div class="card-body p-0">', '<div class="card-body p-0"><div class="table-responsive">'
    $content = $content -replace '</table>\s*</div>\s*@if', "</table></div></div>`n    @if"
    $content = $content -replace '</table>\s*</div>\s*@endif', "</table></div></div>`n@endif"
    Set-Content $file $content -NoNewline
    Write-Host "Updated: $file"
}
Write-Host "All done!"