FROM maven:3.8.6-amazoncorretto-8 AS builder
WORKDIR /app
COPY ./web-service .
RUN --mount=type=cache,target=/root/.m2 mvn clean package

FROM amazoncorretto:8
WORKDIR /app
COPY --from=builder /app/target/ .

ADD https://github.com/ufoscout/docker-compose-wait/releases/download/2.9.0/wait /wait
RUN chmod +x /wait
RUN /wait
ENTRYPOINT java -jar fa.jar