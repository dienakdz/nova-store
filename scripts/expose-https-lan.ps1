param(
	[Parameter(Mandatory = $true)]
	[string]$LanAddress,

	[int]$Port = 8443
)

$ErrorActionPreference = 'Stop'

$principal = New-Object Security.Principal.WindowsPrincipal(
	[Security.Principal.WindowsIdentity]::GetCurrent()
)

if (-not $principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)) {
	throw 'Run PowerShell as Administrator before executing this script.'
}

$lanIp = Get-NetIPAddress -AddressFamily IPv4 |
	Where-Object { $_.IPAddress -eq $LanAddress }

if (-not $lanIp) {
	throw "LAN address $LanAddress is not assigned to this Windows machine."
}

$wslAddresses = (wsl.exe hostname -I).Trim() -split '\s+'
$wslAddress = $wslAddresses |
	Where-Object { $_ -match '^\d{1,3}(\.\d{1,3}){3}$' } |
	Select-Object -First 1

if (-not $wslAddress) {
	throw 'Could not determine the WSL IPv4 address.'
}

# Replace only Nova Store's exact LAN listener. WSL's address can change after restart.
netsh interface portproxy delete v4tov4 `
	listenaddress=$LanAddress `
	listenport=$Port 2>$null | Out-Null

netsh interface portproxy add v4tov4 `
	listenaddress=$LanAddress `
	listenport=$Port `
	connectaddress=$wslAddress `
	connectport=$Port | Out-Null

$firewallRuleName = "Nova Store HTTPS $Port"
$existingRule = Get-NetFirewallRule -DisplayName $firewallRuleName -ErrorAction SilentlyContinue

if ($existingRule) {
	$existingRule | Set-NetFirewallRule -Enabled True -Profile Private -Action Allow
} else {
	New-NetFirewallRule `
		-DisplayName $firewallRuleName `
		-Direction Inbound `
		-Action Allow `
		-Protocol TCP `
		-LocalPort $Port `
		-Profile Private | Out-Null
}

Write-Host "Forwarding https://${LanAddress}:${Port} to WSL ${wslAddress}:${Port}."
Write-Host 'Re-run this script after a WSL restart if the WSL IP changes.'
