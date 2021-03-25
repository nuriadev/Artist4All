import { Injectable } from '@angular/core';
import { CanActivate, Router } from '@angular/router';
import { SessionService } from '../services/session.service';

@Injectable({
  providedIn: 'root',
})
export class AuthGuard implements CanActivate {

  constructor(private _router: Router, private _sessionService: SessionService) { }

  canActivate() {
    if (this._sessionService.isAuthenticated()) {
      return true;
    }
    this._router.navigate(['/login']);
    return false;
  }
}
