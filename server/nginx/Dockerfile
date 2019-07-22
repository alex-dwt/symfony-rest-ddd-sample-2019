FROM nginx:1.15.5-alpine

RUN apk add --update nodejs

COPY config /etc/nginx/

CMD ["sh",  "-c", "/etc/nginx/boot.bash"]