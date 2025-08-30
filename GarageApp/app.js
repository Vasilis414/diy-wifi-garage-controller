const {
  exec
} = require("node:child_process");
exec('php -S <yourdeviceip>:5500', (err, output) => {})