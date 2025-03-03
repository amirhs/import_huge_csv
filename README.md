
# Code Challenge - Import huge list of employee as CSV file

## Overview

>Hi, my name is Amir Hosseini, and I'm glad to have had the opportunity to work on such an interesting task. I appreciate the chance to contribute to it.

>When dealing with large file imports into a database, there are several important considerations. The first challenge, in my view, is handling file uploads efficiently. In a real-world scenario, it would be beneficial to separate the upload process from the import service. Once the file is uploaded, the API returns the file ID, and the frontend can then trigger the import process. The file can be compressed with Gzip before being sent to the API.

>To prevent memory overhead and reduce network load, I used batch processing and queues. In a real-world scenario, I would also implement authentication and authorization for security, as well as encrypt data exchanged between services.

>For invalid rows in the CSV file, I implemented validation to separate them into a separate CSV file and send it to the user for correction.

## Tech Stack

- **Language:** [PHP]
- **Framework:** [Symfony 7]
- **Database:** [MySQL]
- **Queue:** [RabbitMQ]
- **Other Tools:** [Docker]

---

### Build and Run
```shell
docker compose -p localbrandx up -d
```

### Stop and Remove the Container
```shell
docker compose -p localbrandx up -d --build app
```

### Run Job to consume and import data to MySQL
```shell
docker exec -it app php bin/console messenger:consume async --limit=10 --memory-limit=512M
```


### To Upload File
```shell
curl -X POST -F "file=@/home/amir/Downloads/import.csv" http://localhost:8002/api/employee
```

### To Import the File
```shell
curl -X POST http://localhost:8002/api/employee/import/1
```


