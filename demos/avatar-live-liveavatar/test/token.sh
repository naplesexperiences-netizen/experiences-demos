#!/usr/bin/env bash
#
# Genera un session_token LiveAvatar da incollare nella pagina demo.
#
#   export LIVEAVATAR_API_KEY="la-tua-chiave"
#   ./test/token.sh
#
# Ogni token vale per UNA sola sessione: per una nuova prova, rilancia.

set -euo pipefail

AVATAR_ID="${AVATAR_ID:-c72a9099-84b9-4d5d-98f4-a19ba131e654}"
CONTEXT_ID="${CONTEXT_ID:-06d11e63-ff70-4c2b-b0a3-aad3f1134013}"
LANGUAGE="${LANGUAGE:-it}"
MAX_SECONDS="${MAX_SECONDS:-300}"

if [ -z "${LIVEAVATAR_API_KEY:-}" ]; then
    echo "ERRORE: manca la API key." >&2
    echo '  export LIVEAVATAR_API_KEY="la-tua-chiave"' >&2
    exit 1
fi

payload=$(cat <<JSON
{
  "mode": "FULL",
  "avatar_id": "$AVATAR_ID",
  "avatar_persona": { "context_id": "$CONTEXT_ID", "language": "$LANGUAGE" },
  "interactivity_type": "CONVERSATIONAL",
  "max_session_duration": $MAX_SECONDS
}
JSON
)

response=$(curl -sS -w $'\n%{http_code}' -X POST https://api.liveavatar.com/v1/sessions/token \
    -H "X-API-KEY: $LIVEAVATAR_API_KEY" \
    -H "content-type: application/json" \
    -H "accept: application/json" \
    -d "$payload")

status="${response##*$'\n'}"
body="${response%$'\n'*}"

if [ "$status" != "200" ]; then
    echo "LiveAvatar ha risposto HTTP $status:" >&2
    echo "$body" >&2
    echo >&2
    echo "Manda questo output a Claude: dice quale campo non va." >&2
    exit 1
fi

token=$(printf '%s' "$body" | python3 -c 'import json,sys; print(json.load(sys.stdin)["data"]["session_token"])')

echo
echo "Token pronto — copia la riga qui sotto e incollala nel riquadro giallo della pagina:"
echo
echo "$token"
echo
