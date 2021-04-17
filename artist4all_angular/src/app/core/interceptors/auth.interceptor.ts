import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor,
} from '@angular/common/http';
import { Observable } from 'rxjs';
import { SessionService } from '../services/session.service';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {
  constructor(private _sessionService: SessionService) {}

  intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
      if (request.url.includes('/login') || request.url.includes('/register')) {
         return next.handle(request);
      }
      request = request.clone({
          setHeaders: {
              Authorization: `Bearer ${this._sessionService.getCurrentToken()}`
          }
      });
      return next.handle(request);
  }
}
