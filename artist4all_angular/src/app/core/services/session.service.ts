import { Injectable } from '@angular/core';
import { User } from '../models/user';
import { Router } from '@angular/router';
import { Session } from '../models/session';

@Injectable()
export class SessionService {


  constructor(private _router: Router) { }

  setCurrentSession(session: Session) {
    localStorage.setItem('currentUser', JSON.stringify(session));
  }

  getCurrentSession(): Session {
    return JSON.parse(localStorage.getItem('currentUser'));
  }

  getCurrentUser(): User {
    var session: Session = this.getCurrentSession();
    return session.user;
  };

  getCurrentToken(): string {
    var session = this.getCurrentSession();
    return session.token;
  };

  isAuthenticated(): boolean {
    return (this.getCurrentToken() != null) ? true : false;
  };

  logout(): void {
    localStorage.removeItem("currentUser");
    localStorage.clear();
    this._router.navigate(['']);
  }

}
