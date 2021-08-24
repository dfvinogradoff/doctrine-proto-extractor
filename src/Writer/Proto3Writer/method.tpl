    rpc Upsert{message}(stream {message}) returns (ReplyResponse) {}
    rpc Delete{message}(stream Uuid) returns (ReplyResponse) {}