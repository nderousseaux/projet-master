job "mongo" {
  datacenters = ["dc1"]

  group "mongo" {
    count = 3

    network {
      port "http" {
	to = 27017
      }
    }

    task "mongo" {
      driver = "docker"
      config {
        image = "mongo:7.0.2"
	args = ["--replSet", "rs0", "--bind_ip_all"]
	ports = ["http"]
	volumes = [
		"local:/var"
	]
      }
            
			service {
				name = "mongo"
				port = "http"
			}
	
	template {
		data = <<EOF
var config = {
	"_id": "rs0",
	"version": 1,
	"members": [
		{{ range service "mongo" }}
		{
			"_id": {{ .Port }},
			"host": "{{ .Address }}:{{ .Port }}",
			"priority": 1
		},
		{{ end }}
	]
};
rs.initiate(config);
rs.status();
EOF
		destination = "local/initiate.js"
		change_mode = "noop"
	}

      
      resources {
        cpu    = 200
        memory = 256
      }
    }

    scaling {
      enabled = true
      min = 1
      max = 6
    }
	}
}
