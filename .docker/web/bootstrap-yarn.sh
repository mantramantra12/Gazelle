#!/bin/bash

set -euo pipefail

YARN_CACHE_FOLDER="${CI_PROJECT_DIR}/.yarn-cache"
export YARN_CACHE_FOLDER

yarn
npx browserslist@latest --update-db
yarn dev gazelle
