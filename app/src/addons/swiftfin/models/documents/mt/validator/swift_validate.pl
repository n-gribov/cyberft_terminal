use strict;
use Data::Dumper;
use MIME::Base64 ();

use FindBin;
use lib $FindBin::RealBin . '/lib';
use CyberFT::SWIFT::Document;
use CyberFT::SWIFT::Validator;


main();

sub main {
    eval {
        my $msg = read_message();
        unless (defined $msg) {
            write_result(2, "bad input");
            return;
        }
        
        my $v = validate($msg);
        unless (defined $v && defined $v->{result}) {
            write_result(2, "unknown error");
            return;
        }
        
        write_result($v->{result}, $v->{error});
        return;
    };
    if ($@) {
        write_result(2, "unknown error");;
    }
}

sub read_message {
    my ($sender, $receiver, $format, $type, $body_b64, $body, $priority);
    my $body_section = 0;
    
    # Читаем и разбираем входящее сообщение
    while (my $line = <STDIN>) {
        if (!$body_section && $line =~ /^Begin\s*$/) {
            $body_section = 1;
            next;
        }
        elsif ($body_section && $line =~ /^End\s*$/) {
            $body_section = 0;
            next;
        }
    
        if ($body_section) {
            $body_b64 .= $line;
        } 
        elsif ($line =~ /Content-Type:\s*(\S+)/) {
            my $content_type = $1;
            if ($content_type =~ /swift\/(\S+)/i) {
                $format = 'swift';
                $type = 'MT' . uc($1);
            }
        }
        elsif ($line =~ /From:\s*(\S+)/) {
            $sender = $1;
        }
        elsif ($line =~ /To:\s*(\S+)/) {
            $receiver = $1;
        }
        elsif ($line =~ /Priority:\s*(\S+)/) {
            $priority = $1;
        }
    }
    
    # Тело сообщения в кодировке base64
    if ($body_b64) {
        $body = MIME::Base64::decode($body_b64);
    }
    
    if (defined($format) && defined($type) && defined($body)) {
        return {
            sender => $sender,
            receiver => $receiver,
            format => $format,
            type => $type,
            body => $body,
            priority => $priority,
        };
    }
    
    return undef;
}

sub validate {
    my $msg = shift;
    
    if ($msg->{format} eq 'swift') {
        my $d = CyberFT::SWIFT::Document->new(
            sender => $msg->{sender},
            priority => $msg->{priority},
            receiver => $msg->{receiver},
            type => $msg->{type},
            msg => $msg->{body},
        );
        my $v = CyberFT::SWIFT::Validator->new();
        my $r = $v->validate($d);
        return $r;
    }

    return undef;
}

sub write_result {
    my $err_code = shift;
    my $err_message = shift;
    print $err_code, "\n";
    print $err_message, "\n";
}