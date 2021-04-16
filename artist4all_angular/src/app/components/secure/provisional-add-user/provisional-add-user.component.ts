import { Component, OnInit } from '@angular/core';
import { User } from 'src/app/core/models/user';
import { SessionService } from 'src/app/core/services/session.service';
import { UserService } from 'src/app/core/services/user.service';
import {MatTableDataSource} from '@angular/material/table';

@Component({
  selector: 'app-provisional-add-user',
  templateUrl: './provisional-add-user.component.html',
  styleUrls: ['./provisional-add-user.component.css'],
})
export class ProvisionalAddUserComponent implements OnInit {
  constructor(
    private _userService: UserService,
    private _sessionService: SessionService
  ) {}

  userList: Array<User> = [];
  user = this._sessionService.getCurrentUser();
  token = this._sessionService.getCurrentToken();

  ngOnInit(): void {
    this._userService.getAllOtherUsers(this.user.id, this.token).subscribe(
      (result) => {
        this.userList = result;
        this.userList.forEach((user) => function(){
          this.displayedColumns['username'] = [user.username];
        });
      },
      (error) => {
        console.error(error);
      }
    );
  }

  displayedColumns: string[];
  dataSource = new MatTableDataSource(this.userList);

  applyFilter(event: Event) {
    const filterValue = (event.target as HTMLInputElement).value;
    this.dataSource.filter = filterValue.trim().toLowerCase();
  }
}
